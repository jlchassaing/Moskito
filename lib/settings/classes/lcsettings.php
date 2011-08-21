<?php

/*!

\class lcSettings lcsettings.php
\version 0.1

this class loads ini files and helps get ini values

\author jlchassaing

*/
class lcSettings
{
    private $settings;
    private $conf;
    private $currentfile;

    private static $instance;

    /*!
     *
    Singelton
    \param string $iniFile the default ini file is settings
    \return lcSettings
    */
    public static function getInstance($iniFile = "settings",$reload = false)
    {
        if (!is_array(self::$instance))
        {
            self::$instance = array();
        }

        if ((!isset(self::$instance[$iniFile]) or !self::$instance[$iniFile] instanceof lcSettings) or $reload)
        {
            self::$instance[$iniFile] = new lcSettings($iniFile);
        }
        return self::$instance[$iniFile];
    }


    /*!

    Class Constructor
    loads the ini file from the default settings and the siteaccess
    \param string $iniFile
    */
    function __construct($iniFile = "settings",$loadConf = true)
    {
        if ($loadConf)
        {
            if (isset($GLOBALS['SETTINGS']['currentAccess']))
            {
                $access = $GLOBALS['SETTINGS']['currentAccess'];
            }
            $this->settings = array();
            $conf1 = $this->loadConf("settings/$iniFile.ini");
            $conf2 = $this->loadConf("settings/accesses/$access/$iniFile.ini");
            if (is_array($conf2))
            {
                $this->settings = array_merge($conf1,$conf2);
            }
            else
            {
                $this->settings = $conf1;
            }
        }


    }

    /*!

    load a configuration file
    \param string $path path to the ini file to load
    */
    public function loadConf($path)
    {
        if (file_exists($path))
        {
            $conf = parse_ini_file($path,true);

            return $conf;
        }
        else
            return false;
    }

    /*!

    Check if the specified group is delared in the loaded ini array
    \param string $group
    \return boolean
    */
    public function hasGroup($group)
    {
        return isset($this->settings[$group]);
    }

    /*!

    Check if the specified value is delared in the specified group
    \param string $group
    \param string $value
    */
    public function hasValue($group,$value)
    {
        return isset($this->settings[$group][$value]);
    }

    /*!

    Return a group definition
    \param string $group
    \return array
    */
    public function group($group)
    {
        return $this->settings[$group];
    }

    /*!

    Returns the sepcified value declared in the specified group
    \param string $group
    \param string $value
    \return mixed
    */
    public function value($group,$value)
    {
        if (isset($this->settings[$group][$value]))
        {
            $data = $this->settings[$group][$value];
            if (is_string($data))
            {
                $aData = explode(";", $data);
                if (is_array($aData) and count($aData) > 1)
                {
                    return $aData;
                }
            }

            return (is_numeric($data))?(int) $data:$data;
        }
        return null;
    }

    /*!
     	static function to load a specific ini file. This is used when we need
     	to save ini settings
     	\params string $file the ini file to load.
     	\return lcSettings
     */
    public static function loadIniFile($file)
    {
        $settings = new self("no",false);
        $settings->loadFile($file);
        return $settings;
    }

    public function loadFile($file)
    {

        $this->currentfile = $file;
        $this->conf = $this->loadConf($file);
    }

    public function setValue($group,$name,$value,$file = null)
    {
        if (!is_array($this->conf))
        {
            if ($file !== null )
            {
                $this->loadFile($file);
            }
        }


        if (!isset($this->conf[$group]))
        {
            $this->conf[$group] = array();
        }
        $this->conf[$group][$name] = $value;


    }

    public function saveIniFile()
    {
        if ($this->currentfile != "" AND is_array($this->conf))
        {
            if (file_exists($this->currentfile))
            {
                copy($this->currentfile,$this->currentfile.".saved");
            }

            $iniFile = fopen($this->currentfile,'w');
            if (!$iniFile)
            {
                lcDebug::write("ERROR","Can't open ".$this->currentfile." for writing");
            }

            foreach ($this->conf as $key=>$value)
            {
                if (is_array($value))
                {
                    fwrite($iniFile, "\n[$key]\n");
                    foreach ($value as $skey=>$sVal )
                    {
                        if (is_array($sVal))
                        {
                            foreach($sVal as $ssKey=>$ssVal)
                            {

                                $arrayKey = "";
                                if (is_string($ssKey))
                                {
                                    $arrayKey = $ssKey;
                                }
                                fwrite($iniFile,"$skey"."[".$arrayKey."]=".$ssVal."\n");
                            }
                        }
                        else
                        {
                            fwrite($iniFile,"$skey=$sVal\n");
                        }
                    }
                }
            }
            fclose($iniFile);
        }

    }

    /*!

    Get staticaly a specified value from a group in a unic ini file
    \param string $group
    \param string $value
    \param string $path
    \return mixed
    */
    public static function getValue($group,$value,$path)
    {
        if (file_exists($path))
        {
            $conf = parse_ini_file($path,true);

            if (isset($conf[$group][$value]))
            {
                return $conf[$group][$value];
            }
            else
            {
                lcDebug::write("ERROR", "Setting with group : $group, value $value dosn't exist in file : $path");
                return null;
            }
        }
        else
        {
            lcDebug::write("ERROR", "Settings file $path dosn't exist.");
            return null;
        }
    }


}