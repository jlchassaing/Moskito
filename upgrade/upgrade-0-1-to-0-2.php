<?php

class UpgradeScriptIterator implements Iterator
{
    protected $current;

    protected $operationList;

    protected $operationResult;

    protected $currentStep;

    function __construct()
    {
        $this->operationList = $this->init();
        $this->current = 0;
    }

    function rewind()
    {
        $this->current = 0;
    }

    function current()
    {

        return $this->operationList[$this->current];
    }

    function key()
    {
        return $this->current;
    }

    function next()
    {
        ++$this->current;
    }

    function valid()
    {

        return isset($this->operationList[$this->current]);
    }

    function run()
    {
        foreach ($this as $key=>$item)
        {
            $this->operationResult[$item['name']] = $this->$item['method']();
        }
        return $this->operationResult;
    }
}

class UpgradeScript extends UpgradeScriptIterator
{

    function init()
    {
        return array( array("method" => "addInfoTable",
                                             "name"   => "add Info table"),
        array("method" => "upgradeMenuTable",
                                                 "name" => "add section to menu"),
        array("method" => "fixObjectNames",
                                              "name" => "Fix object names in menu"));
    }

    protected function addInfoTable()
    {
        $sqlRequest = " CREATE TABLE IF NOT EXISTS `info` (
                          `name` varchar(255) NOT NULL,
                          `value` varchar(255) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $result = false;
        $db = lcDB::getInstance();
        $db->query($sqlRequest);
        if ($db->isError())
        $result = false;
        else
        {
            $sqlRequest = "INSERT INTO info set name='release',value='0.2'";
            $db->query($sqlRequest);
            if ($db->isError())
            {
                $result = false;
            }
        }


        return $result;
    }

    protected function upgradeMenuTable()
    {
        $query[] = "CREATE TABLE IF NOT EXISTS `sections` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          PRIMARY KEY (`id`),
                          KEY `name` (`name`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";

        $query[] = "INSERT INTO `sections` (`id`, `name`) VALUES
                            (2, 'prive'),
                            (1, 'standard');";

        $query[] = "ALTER TABLE `menu` ADD `section_id` INT NOT NULL";

        $query[] = "UPDATE `menu` set `section_id`=1 ";


        $result = false;
        $db = lcDB::getInstance();
        $id = 0;
        $nbQuery = count($query);
        $noError = true;
        while ($noError and $id < $nbQuery)
        {
            $db->query($query[$id]);
            if ($db->isError())
                $noError = false;
            $id++;
        }

        $result  = $noError;

        return $result;
    }

    protected function fixObjectNames()
    {
        // fixing name of objects in menu
        $menuContents = lcContentMenu::fetchAll();
        foreach ($menuContents as $menu)
        {
            $object = lcContentObject::fetchById($menu->attribute('contentobject_id'),$menu->attribute('lang'),true);
            $objectName = $object->buildName();
            $menu->setAttribute('name',$objectName);
            $menu->store();
            $object->store();
        }

        // try to figure out if there are object that are not in menu

        $query = "SELECT * FROM contentobjects where id not in (select contentobject_id from contentmenu)";
        $db = lcDB::getInstance();
        $result = $db->query($query);
        foreach ($result as $value)
        {
            $object = new lcContentObject($value);
            $object->store();
        }

        $result = true;
        return $result;
    }




}




?>