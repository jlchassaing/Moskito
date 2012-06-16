<?php

/*!
 \class lcSection lcsection.php
 \version 0.1

 */
class lcSection extends lcPersistent
{
	const DEFAULT_SECTION_ID = 1;

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

    public static function getList()
    {
        return self::fetch(self::definition(),
                           null,
                           null,
                           null,
                           null,
                           false,
                           true);
    }


    public function addTreeInSection($nodeId)
    {

    }

    public static function fetchAll()
    {
        $list = self::fetch(self::definition(),null,null,null,null,true,true);

        return $list;

    }

    public function elementsCount()
    {
       $count= self::fetchCount(lcMenu::definition(),array('section_id' => $this->attribute('id')));
       return $count;
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