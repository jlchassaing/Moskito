<?php

/*!
 \class lcSection lcsection.php
 \version 0.1

 */
class lcSection extends lcPersistent
{

    /*!
      constructor
     */
    function __construct($row = null)
    {
        parent::__construct($row,self::definition());
        if (is_array($row))
        {
            foreach($row as $key=>$value)
            {
                $this->setAttribute($key, $value);
            }
        }
    }

    /*!

    Class definition
    \return array
    */
    public static function definition()
    {
        return array('tableName'=>'sections',
    					 'className'=>'lcsection',
    					 'fields' => array('id'=> array('type'=> 'integer'),
    									   'name' => array('type' => 'string')
        ),
    					 'key' => 'id'
        );
    }


    public function addTreeInSection($nodeId)
    {

    }

    public static function fetchAll()
    {
        $db = lcDB::getInstance();

        $query = "SELECT sections.*, count(menu.section_id) as count_nodes ".
                 "FROM sections, menu ".
                 "WHERE menu.section_id = sections.id";

        $list = $db->arrayQuery($query);

        return $list;

    }

    public static function fetchByName($sectionName)
    {
        $cond = array('name' => $sectionName);
        return self::fetch(self::definition(),$cond,null,null,null,true);
    }

    public static function add($sectionName)
    {
        $newSection = new lcSection(array('name' => $sectionName));
        $newSection->store();
    }

    protected $name;
    protected $value;




}