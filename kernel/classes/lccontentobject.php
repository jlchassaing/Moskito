<?php

/*!
  \class lcContentObject lccontentobject.php
  \ingroup lcKernel
  \version 1.0
  \author Jean-Luc Chassaing

  \brief handels  content objects

  holds object definition

*/

class lcContentObject extends lcPersistent
{
	private $data_map;
	private $content_menu;
	private $menu;
	protected $object_name;
	protected $created;
	protected $updated;
	protected $class_identifier;
	protected $node_id;
	protected $lang;
	protected $id;

	/*!
	 class persistent attributes definition
	 \return array
	 */
	static function definition()
	{
		return array('tableName' => 'contentobjects',
		    		 'className' => 'lcContentObject',
					 'fields' 	 => array('id'  	 		=> array('type' => 'integer'),
									   	  'object_name'    	=> array('type' => 'string'),
									   	  'created'  		=> array('type' => 'integer'),
									   	  'updated'  		=> array('type' => 'integer'),
										  'class_identifier' => array('type' => 'string'),
										  'node_id' 		=> array('type' => 'integer')
		),
					 'key' => 'id'

					 );
	}

	/*!

	 contentobject fetcher get object by objectId
	 \param integer $objectId the contentobject id to fetch
	 \param string 	$lang the language
	 \param boolean $object if set to true the function will return an object
	 \return mixed.

	 */
	public static function fetchById($objectId,$lang=null,$asObject = true)
	{

		$object = self::fetch(self::definition(), array('id' => $objectId),null,null,null,$asObject);

		return $object;

	}
	/*!
	 * AttributeFilter :
	 *     array('AND',array('article/name','=','test'))
	 */
	public static function fetchList($parentNodeId,$classFilterType = null, $classFilterArray = null, $attributeFilter = null, $sortBy = null,$asObject = false)
	{
	    $classfilterQuery = "";
	    if ($classFilterType !== null )
	    {
	        if (is_array($classFilterArray) and count($classFilterArray) > 0)
	        {
	            if ($classFilterType == 'include')
	            {
	                $classfilterQuery = " AND contentobjects.class_identifier IN (";
	                $classfilterQuery .= "'".implode("', '",$classFilterArray)."'";
	                $classfilterQuery .= ") ";
	            }    
	        }
	    }
	    
	    $attributeFilterQuery = "";
	    if (is_array($attributeFilter))
	    {
	        $BoulCond = 'AND';
	        $conds = array();
	        if (is_string($attributeFilter[0]))
	        {
	            if ($attributeFilter[0] == 'AND' or $attributeFilter[0] == 'OR' )
	            {
	                $BoulCond = array_shift($attributeFilter);
	            }
	          
	        }
	        
	        foreach($attributeFilter as $filter)
	        {
	            $attributeNameArray = explode('\\', $filter[0]);
	            
	            $contentClass = lcContentClass::getInstance($attributeNameArray[0]);
	            $def = $contentClass->getFields();
	            if (isset($def[$attributeNameArray[1]]))
	            {
	                $type = $def[$attributeNameArray[1]]['datatype'];
	                $isString = true;
	                if ($type == 'xmlbloc')
	                {
	                    $valueField = 'ltxt_value';
	                }
	                elseif ($type == 'string')
	                {
	                    $valueField = 'txt_value';
	                }
	                else
	                {
	                    $valueField = 'int_value';
	                    $isString = false;
	                }
	               
	            }
	            $attributeFilterQuery .= "AND contentobject_attributes.contentobject_id = contentmenu.contentobject_id ";
	            $attributeFilterQuery .= "AND contentobject_attributes.identifier = '".$attributeNameArray[1]."' ";
	            $attributeFilterQuery .= "AND contentobject_attributes.$valueField ";
	            $attributeFilterQuery .= $filter[1];
	            
	            $value = str_replace("*", "%", $filter[2]);
	            if ($isString)
	                $value = "'$value'";
	            $attributeFilterQuery .= " $value ";
	            
	            
	        }
	    }
	    $query = "SELECT contentobjects.* 
	              FROM contentobjects, contentobject_attributes, contentmenu, menu 
	              WHERE menu.parent_node_id = $parentNodeId 
	                AND contentmenu.node_id = menu.node_id
	                AND contentobjects.id = contentmenu.contentobject_id ";
	            
	    $query .= $classfilterQuery. $attributeFilterQuery;
	    
	    $db = lcDB::getInstance();
	    
	    $result = $db->arrayQuery($query);
	    
	    if (is_array($result))
	    {
	        if ($asObject)
	        {
	            $return = array();
	            foreach ($result as $value) 
	            {
	                $return[] = new self($value);
	               
	            }
	            $result = $return;
	        }
	        return $result;
	    }
	    else
	    {
	        return null;    
	    }
	    
	}

	/*!

	 fetch contentobject by nodeId the node id matches an contentmenu item. Each contentobject
	 is linked to a contentmenu object

	 \param integer $nodeId
	  \param string 	$lang the language
	 \param boolean $object if set to true the function will return an object
	 \return mixed.

	 */
	public static function fetchByNodeId($nodeId,$lang = null,$asObject = true)
	{
		$db = lcDB::getInstance();
		$query = "SELECT contentobjects.* FROM contentobjects,contentmenu ".
				 "WHERE contentmenu.node_id = $nodeId ".
			     "AND contentobjects.id = contentmenu.contentobject_id ";
		$result = $db->arrayQuery($query);
		if (count($result) == 1)
		{
			if (!is_null($lang))
			{
				$result[0]['lang']=$lang;
			}
			return new lcContentObject($result[0]);
		}

	}

	public function path()
	{
	    $contentMenu = lcContentMenu::fetchMenuByObjectId($this->id,$this->lang,false);
	    return $contentMenu['path_string'];
	}



	/*!
	 set object language
	 \param string $lang
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
	}

	/*!
	 get object language
	 \return string
	 */
	public function lang()
	{
		return $this->lang;
	}


	/*!
	 class constructor
	 \param array $row
	 \note the parmametre must be an array that matches the object definition
	 	the lang parameter is an extra parameter that is not part of the persistent
	 	parameters.

	 */
	public function __construct(array $row)
	{
		$this->definition = self::definition();
		if (isset($row['class_identifier']))
		{
			$this->class_identifier = $row['class_identifier'];

		}
		foreach ($row as $key=>$value)
		{
			if ($key == "lang")
			{
				$this->lang = $value;
			}
			else
			{
				$this->setAttribute($key,$value);
			}
		}
		if (!isset($this->created))
		{
			$this->created= time();
		}
		if (!isset($this->lang) )
		{
			$this->lang = $GLOBALS['SETTINGS']['currentLanguage'];
		}
		$this->loadDataMap();
		$this->fetchMenu();

	}
	
	
	public function parent()
	{
	    $db = lcDB::getInstance();
	    $objID = $this->attribute('id');
	    $query = "SELECT contentobjects.* FROM `contentobjects`, contentmenu, menu 
                  WHERE menu.node_id = (select contentmenu.node_id FROM contentmenu WHERE contentobject_id = $objID)
                    AND contentmenu.node_id = menu.parent_node_id
                    AND contentobjects.id = contentmenu.contentobject_id";
	    $res = $db->arrayQuery($query);
	    
	    return new self($res[0]);
	}


	/*!

	 get an object attribute
	 this attribute can be a persistent attribute declared in the definition array
	 or not.
	 /param string $name the attribute name.
	  \return mixed
	 */
	public function attribute($name)
	{
		if (isset($this->$name))
		{
			return $this->$name;
		}
		elseif (isset($this->data_map[$name]))
		{
			$dataType = $this->data_map[$name]->datatype();
			return $dataType->getValue($this->data_map[$name]);
		}
		else
		{
			return null;
		}
	}

	/*!
	 get the object data Map. the datamap is a set of all the object persistent attributes
	 those attributes are instances of lcContentObjectAttribute
	 \return array
	 */
	public function dataMap()
	{
		return $this->data_map;
	}

	/*!
	 set an object attribute value. Can be an object attribute or an attribute declared
	 in the definition set
	 */
	public function setAttribute($name,$value)
	{
		if (!isset($this->$name))
		{

			$this->$name = (is_numeric($value))?(int) $value:stripcslashes($value);
			return true;
		}
		elseif(isset($this->data_map[$name]))
		{
			$this->data_map[$name]['value'] = (is_numeric($value))?(int) $value:stripcslashes($value);
			return true;
		}
		return false;
	}

	public function hasAttribute($name)
	{
		$result = false;
		if (isset($this->$name) or (isset($this->data_map[$name]) AND $this->data_map[$name]->hasContent()))
		{
			$result = true;
		}
		return $result;
	}

	/*!
	 build the object name in regard of the corresponding contentclass definition
	 */
	public function buildName()
	{
		if (is_string($this->class_identifier))
		{
			$contentClass = lcContentClass::getInstance($this->class_identifier);
			$contentName= "";
			$namingRule = $contentClass->getNamingRule();
			$field = $this->attribute($namingRule);
			/*
			 $this->object_name =  $this->makeNormName($field);

            $index = 1;
            while ($this->nameExists($field))
            {
                if (preg_match("#(\w+)(_[0-9])+#", $field,$res))
                {
                    $field = $res[1]."_".$index;
                }
                //$field .= "_". $index;
                $index++;
            }
            */
			$this->object_name =  $field;
		}
		return $this->object_name;


	}

	/*
	 * removed in release 0.4
	 public function nameExists($name)
	{
	    $db = lcDB::getInstance();
	    $idCond = "";
	    if (isset($this->id))
	    {
	        $idCond = "and id!=".$this->id;
	    }
	    $query = "SELECT count(*) as nb from contentobjects where object_name='$name' $idCond";
	    $res = $db->arrayQuery($query);
	    if ($res[0]['nb'] > 0)
	    {
	        return true;
	    }
	    else
	    {
	        return false;
	    }
	}
	*/

	/*!
	  retuns the object urlAlias
	  \return string
	 */
	public function urlAlias()
	{
		$lang = $GLOBALS['SETTINGS']['currentLanguage'];

		$contentMenu = lcContentMenu::fetchMenuByObjectId($this->id, $lang);
		if (isset($contentMenu['path_string']))
		{
		    $path = $contentMenu['path_string'];
		}
		else
		{
		    $path = "object/view/full/".$this->id;
		}

		$url = lcHTTPTool::buildUrl($path);
		return $url;
	}


	public function fetchMenu()
	{
	    $contentMenu = lcContentMenu::fetchByObjectId($this->attribute('id'),$this->lang,true);
	    if ($contentMenu instanceof lcContentMenu)
	    {
	       $this->content_menu = $contentMenu;
           $this->menu = lcMenu::fetchById($this->content_menu->attribute('node_id'),true);

	    }

	}







    /*!
     load the object datamap array
     */
	public function loadDataMap()
	{
		if (is_string($this->class_identifier))
		{
			$settings = lcSettings::getInstance();
			$lang = (isset($this->lang))?$this->lang:$settings->value('lang','current');
			$contentClass = lcContentClass::getInstance($this->class_identifier);
			$dataFieldsDefinition = $contentClass->getFields();
			$this->data_map = lcContentObjectAttribute::loadAttributes($dataFieldsDefinition, $lang, $this->id);

		}
	}

	/*!
	 remove an object from the database
	 */
	public function removeObject()
	{

		foreach ($this->data_map as $value)
		{
			$value->removeAttribute();
		}

		self::remove($this->definition,array("id" => $this->id));
	}

	/*!
	 store the object and all its contentobjectattributes part of the datamap.
	 */
	public function store()
	{
		$db = lcDB::getInstance();
		$db->begin();

		$this->buildName();

		$newInsert = false;
		$ObjectId = parent::store();
		if (!isset($this->id) AND is_integer($ObjectId))
		{
			$this->setAttribute("id",$ObjectId);
			$newInsert = true;
		}

		foreach ($this->data_map as $attribute)
		{
			/*if ($newInsert)
			{
				$attribute->setAttribute('contentobject_id',$ObjectId);
			}*/

			$attribute->storeAttribute($this);
		}
		$db->commit();

	}
}


?>