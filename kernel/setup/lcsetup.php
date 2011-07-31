<?php

/*!
 \class lcSetup lcsetup.php
\author jean-Luc Chassaing

handles the installation steps

*/
class lcSetup
{
    private $position;
    private $stepsList = array(array("name"=> "Chek directory access", "callback" => "checkdir"),
    array("name" =>"Get mysql config","callback" => "dbsettings"),
    array("name" =>"Select database", "callback" => "selectdb"),
    array("name" =>"Create admin user", "callback" => "adminuser"),
    array("name" =>"Last step", "callback" => "laststep"));
    private $keys;

    private $datas;

    public function __construct($position = null)
    {
        $this->keys = array_keys($this->stepsList);
        if (is_int($position))
        {
            $this->position = $position;
        }
        else
        {
            $this->position = 0;
        }
    }

    public function next()
    {
        if ($this->position < count($this->stepsList))
        {
            $this->position++;
        }
        else
        {
            $this->position = false;
        }
        return $this->position;
    }

    public function getStepId()
    {
        return $this->position;
    }


    public function isLast()
    {
        $nbActions = count($this->stepsList);
        if ($this->position == $nbActions -1 )
        {
            return true;

        }
        else
        {
            return false;
        }
    }

    /*!
     run a specific installation setp
    return an array with two variables title and message
    \return array
    */
    public function runStep()
    {
        $result = false;
        if (isset($this->stepsList[$this->position]))
        {
            $stepCallbackFunction = $this->stepsList[$this->position]["callback"];
            if (method_exists($this, $stepCallbackFunction))
            {
                $result = call_user_func(array($this,$stepCallbackFunction));
            }
        }

        return $result;
    }

    /*!
     return the list of the installation steps
    \return array
    */
    public function steps()
    {

        return $this->stepsList;
    }


    public function checkdir()
    {
        $title = "Write access";
        $message['write'] = true;
        $skip = false;
        $dirForWriteAccess = array('var/','settings/');
        $isWritable = true;
        $i = 0;

        $message['create'] = true;

        foreach ($dirForWriteAccess as $dir)
        {
            if (!file_exists($dir))
            {
                if (!@mkdir($dir,0775))
                {
                    $message['create'] = false;
                }
            }
        }

        while($isWritable and $i < count($dirForWriteAccess))
        {
            $isWritable = is_writable($dirForWriteAccess[$i]);
            $i++;
        }
        if (!$isWritable)
        {
            $message['write'] = false;
        }
        else
        {
            $skip = true;
        }
        return array("title"     => $title,
                     "message"   => $message,
                     "skip"      => $skip);
    }

    public function dbsettings()
    {
        $title = "Datatabase settings";
        $message = "";
        $skip = true;
        $group = "DBparams";
        $dbConnection = false;

        $http = lcHTTPTool::getInstance();

        $hostName = ($http->hasPostVariable("DBHostNameValue"))?$http->postVariable("DBHostNameValue"):"";
        $hostPort = ($http->hasPostVariable("DBPortNameValue"))?$http->postVariable("DBPortNameValue"):"";
        $dbUser = ($http->hasPostVariable("DBUserNameValue"))?$http->postVariable("DBUserNameValue"):"";
        $dbPasswd = ($http->hasPostVariable("DBPasswordNameValue"))?$http->postVariable("DBPasswordNameValue"):"";

        if ($hostName != "")
        {
            $dbConnection = lcDB::testMysqlConnect($hostName, $dbUser, $dbPasswd);
            if ($dbConnection)
            {
                $settings = lcSettings::loadIniFile("settings/settings.ini");

                $settings->setValue($group, "host", $hostName);
                $settings->setValue($group, "port", $hostPort);
                $settings->setValue($group, "user", $dbUser);
                $settings->setValue($group, "password", $dbPasswd);

                $settings->saveIniFile();
            }
            else
            {
                $message = "Connection could not be made with those parameters.";
                $skip = false;
            }
        }


        $settings = lcSettings::getInstance("settings",true);
        $hostName = $settings->value($group, "host");
        if ($hostName == "")
        {
            $skip = false;
        }


        return array("title"     => $title,
                             "message"   => $message,
                             "skip"      => $skip);
    }

    public function selectdb()
    {
        $title = "Select database";
        $message = "Choose a Database";
        $skip = false;
        $http = lcHTTPTool::getInstance();
        $datas = false;

        if ($http->hasPostVariable("DataBaseNameValue"))
        {
            $dataBaseName = $http->postVariable("DataBaseNameValue");

            $iniSettings = lcSettings::getInstance();
            $dbparams = $iniSettings->group("DBparams");

            if (lcDB::testMysqlConnect($dbparams['host'], $dbparams['user'], $dbparams['password'],$dataBaseName))
            {
                $settings = lcSettings::loadIniFile("settings/settings.ini");

                $settings->setValue("DBparams", "database", $dataBaseName);

                $settings->saveIniFile();
                $skip = true;
            }
            else
            {
                $message = "Could not connect to the database : $dataBaseName";
                $skip = false;
            }

        }
        else
        {
            $settings = lcSettings::getInstance("settings",true);
            $dbName = $settings->value("DBparams", "database");
            if ($dbName == "")
            {

                $db = lcDB::getInstance();
                $query = "show databases";
                $result = $db->arrayQuery($query);
                $dbs = array();
                foreach ($result as $value)
                {
                    if ($value['Database'] != "information_schema" )
                    {
                        $dbs[] = $value['Database'];
                    }

                }
                $datas = array("dbs" => $dbs);
            }
            else
            {
                $skip = true;
                $db = lcDB::getInstance();
                $db->begin();
                $db->importFile("litecms.sql");
                $db->commit();
            }
        }


        return array("title"     => $title,
                     "message"   => $message,
                     "skip"      => $skip,
                     "datas"     => $datas);
    }

    public function adminuser()
    {
        $title = "Setup admin user";
        $message = "";
        $skip = false;
        $http = lcHTTPTool::getInstance();
        $datas = false;


        $admins = lcUser::getByRole('admin');
        if (count($admins) == 0)
        {
            if ($http->hasPostVariable("SetAdminUser"))
            {
                if ($http->hasPostVariable("AdminLoginValue"))
                {
                    $adminLogin = $http->postVariable("AdminLoginValue");
                }
                else
                {
                    $message["AdminLoginValue"] = "No login was set";
                }

                if ($http->hasPostVariable("AdminEmailValue"))
                {
                    $adminEmail = $http->postVariable("AdminEmailValue");
                    if (!lcStringTools::isEmail($adminEmail))
                    {
                        $message["AdminEmailValue"] = "The given value is not an email.";
                    }
                }
                else
                {
                    $message["AdminEmailValue"] = "No email was set";
                }

                if ($http->hasPostVariable("AdminPasswordValue"))
                {
                    $adminPassword = $http->postVariable("AdminPasswordValue");
                    if (!lcUser::checkPassword($adminPassword))
                    {
                        $message["AdminPasswordValue"] = "Please enter a valid password";
                    }
                    else
                    {
                        if ($http->hasPostVariable("AdminCheckValue"))
                        {
                            $check = $http->postVariable("AdminCheckValue");
                            if ($check !== $adminLogin)
                            {
                                $message["AdminCheckValue"] = "The two values don't match.";
                            }

                        }
                        else
                        {
                            $message["AdminCheckValue"] = "You must write the password again.";
                        }
                    }

                }
                else
                {
                    $message["AdminPasswordValue"] = "No password was set";
                }

                if (!is_array($message))
                {
                    $newUser['login'] = $adminLogin;
                    $newUser['password'] = lcUser::encodePassword($adminLogin, $adminPassword);
                    $newUser['role'] = 'admin';
                    $user = new lcUser($newUser);
                    $user->store();
                    $skip = true;

                }
            }

        }
        else
        {
            $skip = true;
        }


        return array("title"     => $title,
                     "message"   => $message,
                     "skip"      => $skip,
                     "datas"     => $datas);


    }

    public function laststep()
    {
        $settings = lcSettings::loadIniFile("settings/settings.ini");

        $settings->setValue("Modules", "defaultModule", "content");
        $settings->setValue("Modules", "defaultView", "view");
        $settings->setValue("Default", "access", "front");

        $settings->saveIniFile();

        return array("title"     => "Last Step",
                             "message"   => "",
                             "skip"      => true,
                             "datas"     => null);

    }


}