<?php

/*!
 \class lcContentObjectAttribute lccontentobjectattribute.php
\version 0.1
\author Jean-Luc Chassaing

handels content object attributes
*/
class lcContentObjectAttribute extends lcPersistent
{

    private $contentobject_id;
    private $name;
    private $datatype;
    private $int_value;
    private $txt_value;
    private $ltxt_value;
    private $identifier;
    private $content;
    private $lang;
    private $id;
    private $required;
    private $formfield;


    /*!
     class definition
    \return array
    */
    static function definition()
    {
        return array('tableName' => 'contentobject_attributes',
					 'className' => 'lcContentObjectAttribute',
					 'fields' 	 => array('id'  	 		=> array('type' => 'integer'),
										  'contentobject_id'=> array('type' => 'integer'),
									   	  'name'     		=> array('type' => 'string'),
									   	  'int_value'  		=> array('type' => 'integer'),
										  'txt_value' 		=> array('type' => 'string'),
										  'ltxt_value'		=> array('type' => 'string'),
										  'datatype' 		=> array('type' => 'string'),
										  'identifier' 		=> array('type' => 'string'),
										  'lang' 			=> array('type' => 'string')
        ),
					 'key' => 'id'
        );
    }


    public function __construct(array $row)
    {
        $this->definition = self::definition();
        foreach ($row as $key=>$value)
        {
            $this->setAttribute($key,$value);
        }
        $datatypeName = $this->datatype;
        $datatypeName[0] = strtoupper($datatypeName[0]);
        $dataTypeClassName = "lc".$datatypeName."DataType";
        $this->content = new $dataTypeClassName( $this);
    }

    public function setAttribute($name,$value)
    {

        $this->$name = (is_numeric($value))?(int) $value:$value;

    }

    public function attribute($name)
    {

        return $this->$name;

    }

    public function datatype()
    {
        return $this->content;
    }

    public function content()
    {
        return $this->content->content();
    }

    public function hasContent()
    {
        return $this->content->hasContent();
    }

    public static function fetchByObjectId($contentObjectId,$lang)
    {
        $fieldList = self::fetch(self::definition(),
        array('contentobject_id'=>(int)$contentObjectId,
										  'lang' => $lang));
        $dataMap = array();
        foreach ($fieldList as $attribute)
        {
            $dataMap[$attribute['identifier']] = new lcContentObjectAttribute($attribute);
        }
        return $dataMap;
    }

    /**
     *
     * Load an object attribute according to the contentobject id if the object has been created
     * or the class definition
     * @param array $classDefinition
     * @param string $lang
     * @param int $contentObjectId
     * @return lcContentObjectAttribute
     */
    public static function loadAttributes($classFieldsDefinition,$lang,$contentObjectId=null)
    {
        $fieldList = array();
        //$attributes = array();

        if (!is_null($contentObjectId))
        {
            $db = lcDB::getInstance();
            $query = "SELECT * FROM contentobject_attributes WHERE " .
					 "contentobject_id = $contentObjectId " .
					 "AND lang='$lang'";
            $result = $db->arrayQuery($query);

        }
        $fieldContents = array();
        foreach ($result as $val)
        {
            $fieldContents[$val['identifier']] = $val;
        }


        foreach ($classFieldsDefinition as $fieldId => $field)
        {
            if (isset($fieldContents[$fieldId]))
            {
                $fieldDef = array_merge($field,$fieldContents[$fieldId]);
            }
            else
            {
                $fieldDef = $field;
                if (isset($lang))
                {
                    $fieldDef['lang'] = $lang;
                }
                if (isset($contentObjectId))
                {
                    $fieldDef['contentobject_id'] = $contentObjectId;
                }
            }
            $fieldList[$fieldId] = new self($fieldDef);
            //$attributes[]= $item['identifier'];
        }
        return $fieldList;
    }





    public function removeAttribute()
    {
        if ($this->content->remove($this))
        {
            self::remove($this->definition,array('contentobject_id'=>$this->contentobject_id));
        }
    }






}


?>