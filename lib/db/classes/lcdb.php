<?php


/*!
 \class lcDB lcdb.php
\version 0.1

Database interrogation class
does the dB connection and performs the request
\author jlchassaing

*/
class lcDB
{
    /*!
     *
    singleton instance
    \var lcDB
    */
    private static $instance;

    /*!
     *
    the database connection
    \var unknown_type
    */
    private $connection;

    /*!
     database array conf
    \var array
    */
    private $dbConf;


    private $nbBegin;

    /*!
     *
    Singleton function
    \return lcDB
    */
    public static function getInstance()
    {
        if (!self::$instance instanceOf lcDB)
        {
            self::$instance = new lcDB();
        }
        return self::$instance;
    }

    /*!
     static function to test the mysql connection
    */
    public static function testMysqlConnect($host,$user,$password,$database = null)
    {

        $con = @mysql_connect($host,$user,$password);
        if ($con)
        {
            if ($database !== null)
            {

                if (!mysql_select_db($database,$con))
                {
                    return false;
                }

            }
            return true;
        }
        else
        {
            return false;
        }


    }

    /*!
     *
    constructor does the mysql connection
    */
    private function __construct()
    {
        $this->loadDBConf();

        $this->connection = mysql_pconnect($this->dbConf['host'],$this->dbConf['user'],$this->dbConf['password']);
        if (!$this->connection)
        {
            lcDebug::write("ERROR", "Can't connect to database : ".mysql_error());
        }
        else
        {
            mysql_set_charset("utf8",$this->connection);
            if (isset($this->dbConf['database']))
            {
                if (!mysql_select_db($this->dbConf['database'],$this->connection))
                {
                    lcDebug::write("ERROR", "Can't connect to database :".mysql_error());
                }
            }
            else
            {
                lcDebug::write("ERROR", "No database is configured");
            }

        }
        $this->nbBegin = 0;
    }

    /*!
     *
    query function that return an array for result
    the array keys are the table fields.
    \param string $query
    */
    public function arrayQuery($query)
    {
        $result = $this->query($query);
        $array = array();
        $res = array();
        if (!$result)
        {
            lcDebug::write("ERROR", "The query : $query did not return any valid result.");
            return false;
        }
        else
        {
            while(($line = mysql_fetch_array($result)) == true)
            {
                foreach ($line as $key=>$value) {

                    if (is_string($key))
                    {
                        if (is_numeric($value))
                        {
                            $res[$key] = (int) $value;
                        }
                        else
                        {
                            $res[$key] = $value;
                        }
                    }

                }
                $array[] = $res;
            }
            return $array;
        }

    }



    public function query($query)
    {
        $result = mysql_query($query,$this->connection);
        if (mysql_error($this->connection))
        {
            lcDebug::write("ERROR", "Mysql Error : ". mysql_errno($this->connection) ." :" . mysql_error($this->connection));
        }
        else
        {
            lcDebug::write("NOTICE", "Mysql query : $query");
        }
        return $result;
    }

    public function begin()
    {
        if ($this->nbBegin == 0)
        {
            $this->query("start transaction");
        }
        else
        {
            $this->nbBegin++;
        }


    }


    public function isError()
    {
        if (mysql_error($this->connection))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function commit()
    {
        if ($this->nbBegin == 0 && !$this->isError())
        {
            $this->query("commit");
        }
        elseif ($this->isError())
        {
            $this->query("rollback");
        }
        else
        {
            $this->nbBegin--;
        }

    }

    public function rollback()
    {
        $this->query("rollback");
        $this->nbBegin =0;
    }


    public function lastId()
    {
        return mysql_insert_id($this->connection);
    }



    /*!

    Build query conds based on the $cond parameter
    \param array $cond
    \return string
    */
    public function buildCond($cond)
    {
        $condString = "";
        $isFirstField = true;
        if (is_array($cond))
        {
            foreach ($cond as $key=>$value)
            {
                if (!$isFirstField)
                {
                    $condString .=" AND ";
                }
                else
                {
                    $isFirstField = false;
                }
                if (is_array($value) and count($value) == 1)
                {
                    $value = $value[0];
                }

                if (is_array($value))
                {
                    $list = "";
                    foreach ($value as $id=>$item)
                    {
                        $list .= ($id > 0)?", ":"";
                        $list .= (is_numeric($item))?$item:"'$item'";

                    }
                    $condString ="$key in ($list)";
                }
                elseif (is_string($value))
                {
                    $value = mysql_real_escape_string($value);
                    $compareOperator = "=";
                    if (strpos($value,"%") !== false)
                    {
                        $compareOperator = "LIKE";
                    }
                    $condString .= "$key $compareOperator '$value'";
                }
                else
                {
                    $condString .= "$key = $value";
                }

            }
        }
        else
        {
            return false;
        }
        return $condString;
    }

    public function buildSort($order)
    {
        $orders = "";
        foreach ($order as $key=>$value)
        {

            if ($orders != "")
            {
                $orders .= ", ";
            }
            if (is_integer($key))
            {
                $keys = array_keys($value);
                $orders.= ($value[$keys[0]])?$key[0]." asc":$key[0]." desc";
            }
            else
            {
                $orders .= 	($value)?$key." asc":$key." desc";
            }
        }
        return $orders;
    }

    /*!

    database configuration loader
    */
    private function loadDBConf()
    {
        $settings = lcSettings::getInstance();
        $this->dbConf = $settings->group('DBparams');
    }

    public static function cleanData($data)
    {
        return mysql_real_escape_string($data);
    }


    public function importFile($file)
    {
        if (file_exists($file))
        {
            $f  = fopen($file,'r');
            $query = "";


            if ($f)
            {
                while (($line = fgets($f)) !== false)
                {
                    if ($line !== "")
                    {
                        $begin = substr($line, 0,2);
                        //ignore comment lines;
                        if ( $begin != "--" AND $begin != "/*")
                        {
                            $query .= $line;
                            if (strpos($query,";") > 0)
                            {
                                // execute query
                                $this->query($query);
                                $query = "";

                            }
                        }

                    }
                }
            }

        }
        else
        {
            lcDebug::write("ERROR", "The file $file doesn't exist.");
            return false;
        }
    }
}

?>