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
        if (is_array($result) and count($result) > 0)
        {
            return $result;
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



}



?>