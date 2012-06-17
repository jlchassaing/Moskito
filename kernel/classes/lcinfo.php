<?php

class lcInfo
{

    const DEFAULT_RELEASE = "0.1";

    function __construct()
    {

    }


    public static function getInfos()
    {
        $db = lcDB::getInstance();
        $query = "select * from info";
        $result = $db->arrayQuery($query);
        $infos = array();
        if (is_array($result) and count($result) > 0)
        {
            foreach ($result as $value)
            {
                $infos[$value['name']] = $value['value'];
            }
            return $infos;
        }
        else
        {
            return false;
        }

    }

    public static function getRelease()
    {
        $info = self::getInfos();
        if (!isset($info['release']))
        {
            return self::DEFAULT_RELEASE;
        }
        else
        {
            return $info['release'];
        }
    }

    public static function set($name,$value)
    {
        $query = "UPDATE info set value='$value' where name='$name'";

        $db = lcDB::getInstance();
        $db->query($query);
    }



}



?>