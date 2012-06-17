<?php

/*!

\class  lcContentMenu lcontentmenu.php
\version 0.1

This class deals whith the link that exists between a content and the
menu. Each content version according to its language is related to a node
The path to the content is built on the position of the node in the tree.
The tree is Managed by the lcMenu class.

\author jlchassaing

*/
class lcContentMenu extends lcPersistent
{

    /*!

    Class constructor
    \param array $row
    */
    function __construct(array $row = null)
    {
        $this->definition = self::definition();
        if (is_array($row))
        {
            foreach ($row as $key=>$value)
            {

                $this->setAttribute($key,$value);
            }
        }
    }

    /*!

    Class definition
    \return array
    */
    static function definition()
    {
        return array('tableName' => 'contentmenu',
					 'className' => 'lcContentMenu',
					 'fields' 	 => array('id'					=> array('type' => 'integer'),
					 					  'node_id'  	 		=> array('type' => 'integer'),
									   	  'contentobject_id'	=> array('type' => 'integer'),
									   	  'name'	  			=> array('type' => 'string'),
									      'path_string'			=> array('type'=> 'string'),
										  'lang'				=> array('type' => 'string')
        ),
					 'key' => 'id'
					 );
    }


    /*!

    Fetch the hole content tree
    \param integer $node_id the node frome wich the tree is loade (not use for the moment)
    \param string $lang the language to fetch
    */
    public static function fetchMenuTree($node_id=null,$lang = null,$depth=null,$getParent = false)
    {
        $db = lcDB::getInstance();

        if (is_null($lang))
        {
            $settings = lcSettings::getInstance();
            if ($settings->hasValue("lang",'current'))
            {
                $lang = $settings->value("lang",'current');
            }
        }
        else
        {
            $lang = "fre-FR";
        }

        $node_id = ($node_id === null)?$settings->value('TreeNodes','ContentNode'):$node_id;

        $nodeCond = self::getNodeDepthCond($node_id,$depth,$getParent);


        $selectFields = "contentmenu.id, menu.node_id, (select count(*) from menu as menu2 where menu2.parent_node_id = menu.node_id) as children_count,
                               contentmenu.contentobject_id, contentmenu.name, contentmenu.path_string, contentmenu.lang, ".
       				        " menu.parent_node_id, menu.path_ids, menu.sort_val, contentobjects.created, contentobjects.class_identifier";

        $query = "SELECT $selectFields FROM contentmenu,contentobjects, menu ".
				 "WHERE contentmenu.node_id = menu.node_id ".
			     "AND contentmenu.lang = '$lang' ".
				 "AND contentobjects.id = contentmenu.contentobject_id ".
        $nodeCond .
			     "ORDER BY menu.sort_val ";


        return $db->arrayQuery($query);
    }

    public static function fetchContentTree($nodeId,$classfilterArray = null, $depth = null,$asObject = false)
    {
        $db = lcDB::getInstance();

        $classfilterString = "";
        if (is_array($classfilterArray))
        {
            $classfilterString = " AND contentobjects.class_identifier IN ('";
            $classfilterString .= implode("', '",$classfilterArray)."')";

        }

        $nodeCond = self::getNodeDepthCond($nodeId,$depth);


        $query = "SELECT contentmenu.*, menu.parent_node_id, menu.depth FROM contentobjects, contentmenu, menu WHERE
    			  contentobjects.id = contentmenu.contentobject_id
    			  AND contentmenu.node_id = menu.node_id $classfilterString  $nodeCond
    			  ORDER BY contentmenu.path_string ASC";


        $result = $db->arrayQuery($query);
        if ($asObject)
        {
            foreach ($result as $key=>$item)
            {
                // $result[$key] = new lcContentObject($item);
                 $result[$key]['parent_node_id'] = $item['parent_node_id'];
                 $result[$key]['node_id'] = $item['node_id'];
                 $result[$key]['object'] = lcContentObject::fetchByNodeId($item['node_id']);
            }
        }
        return $result;
    }

    /**!
     *
     * fetch the contentMenuTree to build a tree ordered by path_string
     * This is mainely used to build a tree of content for the left menu in admin
     */
    public static function fetchFullMenu($parentNodeId, $depth = 1, $fetchOnlyObjectsWithChildrens = true)
    {
        $db = lcDB::getInstance();
        $query = "select depth, path_ids FROM menu  WHERE node_id = $parentNodeId";
        $res = $db->arrayQuery($query);
        if (isset($res[0]))
        {
            $parent = $res[0];
        }

        $parentNodeFilterSqlCondition = " ";
        if ($fetchOnlyObjectsWithChildrens)
        {
            $parentNodeFilterSqlCondition = "AND (select count(m2.node_id) FROM menu as m2 WHERE m2.parent_node_id = m.node_id ) > 0 ";
        }

        $searchDepth = $parent['depth'] + $depth;

        $parentSqlCondition = "AND m.path_ids like '".$parent['path_ids']."%' ";
        $depthSqlCondition = "AND m.depth >= {$parent['depth']} AND m.depth <= $searchDepth ";
        $orderSqlCondition = "ORDER BY path_string";
        $query = "SELECT * FROM contentmenu, menu as m WHERE
                  contentmenu.node_id = m.node_id ";
         $query = $query . $parentSqlCondition . $depthSqlCondition . $parentNodeFilterSqlCondition. $orderSqlCondition ;

        $res = $db->arrayQuery($query);
        return $res;

    }

    public static function getNodeDepthCond($nodeId,$depth,$getParent = false)
    {
        $db = lcDB::getInstance();

        $query = "SELECT path_ids FROM menu WHERE node_id = $nodeId";

        $parentNodeId = $db->arrayQuery($query);
        $parentPathId = "/";
        $depthValues = "";

        if ($depth == 1 )
        {
            $depthValues = ($getParent)?"0,1":"1";

        }
        elseif ($depth > 1)
        {
            if ($getParent)
            {
                $depthValues = "0,$depth";
            }
            else
            {
                $depthValues = "1,$depth";
            }
        }



        if (isset($parentNodeId[0]['path_ids']))
        {
            $parentPathId = $parentNodeId[0]['path_ids'];
        }

        $pathIdRegexp ="'^$parentPathId([0-9]+/){".$depthValues."}$'";

        $nodeCond = "";
        $firstItem = 1;
        $depthCond = "+";
        if ($getParent)
        {
            $firstItem = 0;
            $depthCond = "*";
        }


        if ($depth !== null)
        {
            $nodeCond = "AND menu.path_ids REGEXP $pathIdRegexp ";
        }
        else
        {
            $nodeCond = "";
        }

        return $nodeCond;

    }

    /*!

    Fetch a single menu entry, base on the node_Id
    \param integer $node_id
    \param boolean $hasRelatedObject
    \param string $lang

    \retrun array
    */
    public static function fetchMenu($node_id,$hasRelatedObject = false ,$lang =null)
    {
        if (is_null($lang))
        {
            $settings = lcSettings::getInstance();
            if ($settings->hasValue("lang",'current'))
            {
                $lang = $settings->value("lang",'current');
            }
        }
        else
        {
            $lang = "fre-FR";
        }

        if ($hasRelatedObject)
        {
            $hasObjectQuery = "AND contentmenu.contentobject_id IS NOT NULL ";
        }

        $db = lcDB::getInstance();
        $query = "SELECT FROM contentmenu, menu ".
				 "WHERE menu.parent_node_id = $node_id ".
				 "AND contentmenu.node_id = menu.node_id ".
        $hasObjectQuery .
				 "AND contentmenu.lang = '$lang' ";
        return $db->arrayQuery($query);
    }

    /*!
     *
     * Build the path array of a node
     *
     * the returned array is  an array of each element of the path
     * each one is set as an associative array of the node_id, the name and the path_string
     *
     * \param interger $node_id
     * \return array
     */
    public static function pathArray($node_id)
    {
        $db = lcDB::getInstance();

        $query = "SELECT path_ids FROM menu WHERE node_id = $node_id";
        $res = $db->arrayQuery($query);
        $pathIdList = str_replace("/",",", substr($res[0]['path_ids'],1,-1));
        $query = "SELECT node_id, name, path_string
                  FROM contentmenu
                  WHERE node_id in ($pathIdList)
                  ORDER BY path_string";
         $res = $db->arrayQuery($query);

         return $res;
    }

    /*!

    Update children name when a parent node is updated
    */
    public function updateChildrenNames()
    {
        $pathName = $this->makeNormName($this->name);
        $db = lcDB::getInstance();
        $db->begin();
        $query = "SELECT contentmenu.id, menu.node_id, menu.path_ids, contentmenu.path_string ".
				 "FROM ".$this->definition['tableName']. ", menu ".
				 "WHERE contentmenu.node_id = menu.node_id ".
				 "AND menu.path_ids like '%/".$this->node_id."%' ".
				 "AND contentmenu.lang = '".$this->lang."'";
        $aMenuList = $db->arrayQuery($query);
        foreach ($aMenuList as $value)
        {
            $pathIds = explode("/",$value['path_ids']);
            $nodeIndex = array_search($this->node_id,$pathIds);
            $pathString = explode("/",$value['path_string']);
            $pathString[$nodeIndex] = $pathName;
            $newPathString = self::buildUrlAlias(implode("/", $pathString));
            $query = "UPDATE ".$this->definition['tableName'].
					 " SET path_string='$newPathString' ".
					 "WHERE id = ".$value['id'];
            $db->query($query);
        }
        $db->commit();
    }

    /*!

    Update children names when a parent changes position
    \param unknown_type $parentId
    */
    public static function updateAllChildrenPathStrings($parentId)
    {
        $parents = self::fetch(self::definition(),array('node_id'=> $parentId),null,null,null,true);
        if (is_array($parents))
        {
            foreach ($parents as $parent)
            {
                $parent->updateChildrenNames();
            }
        }
        elseif ($parents instanceof lcContentMenu)
        {
            $parents->updateChildrenNames();
        }


    }

    /*!
     *
     Enter description here ...
     \param interger $Id
     \return lcContentMenu
     */
    public static function fetchById($Id)
    {
        $cond = array('id'=>$Id);
        return self::fetch(self::definition(),$cond,null,null,null,true);
    }

    /*!
     *
     Enter description here ...
     \param interger $node_id mneu Node Id
     \param string $lang site language
     \return lcContentMenu
     */
    public static function fetchByNodeId($node_id,$lang=null,$asObject = true,$asList = false)
    {
        $cond = array('node_id'=>$node_id);
        if (!is_null($lang))
        {
            $cond['lang'] = $lang;
        }
        return self::fetch(self::definition(),$cond,null,null,null,$asObject,$asList);
    }


    /*!
     *
     Fetch contentmenu by path string
     \param string $path path to search
     \param string $lang
     \param boolean $asObject
     \return lcContentMenu
     */
    public static function fetchByPath($path,$lang,$asObject = true)
    {
        $cond = array('path_string'=>$path,
					  'lang' => $lang);
        $result = self::fetch(self::definition(),$cond,null,null,null,$asObject);

        return $result;

    }

    /*!

    Remove a menu entry if it has no children
    This methods removes content from lcContentMenu and lcMenu
    \param integer $nodeId
    */
    public static function removeMenu($nodeId)
    {
       // if (!lcMenu::hasChildren($nodeId))
       // {
            $cond = array('node_id' => $nodeId);
            lcMenu::remove(lcMenu::definition(),$cond);
            lcContentMenu::remove(self::definition(),$cond);
       // }

    }

    /*!

    Fetch menu entry by contentobjec_id
    \param integer $objectId
    \param string $lang
    \param boolean $asObject
    \param boolean $returnlist if set to true the even if there is one result it will
    be returned in an array.
    */
    public static function fetchMenuByObjectId($objectId,$lang = null,$asObject = false,$returnlist = false)
    {
        $cond = array('contentobject_id'=>$objectId);
        if (!is_null($lang))
        {
            $cond['lang'] = $lang;
        }

        $query = "SELECT * FROM contentmenu, menu ";
        $filter = "";
        foreach ($cond as $key=>$value)
        {
            if ($filter != "")
            {
                $filter = $filter . " AND ";
            }
            if (is_string($value))
            {
                $value = "='$value'";
            }
            elseif (is_array($value))
            {
                $condString = " IN (";
                if (!is_numeric($value[0]))
                {
                    $condString .= "'".implode("','", $value)."')";
                }
                else
                {
                    $condString .= implode(",", $value).")";
                }
                $value = $condString;
            }
            else
            {
                $value = "=$value";
            }
            $filter .= $key.$value;
        }

        $filter .= " AND menu.node_id = contentmenu.node_id";
        $query = $query . " WHERE ".$filter;
        $db = lcDB::getInstance();
        $result = $db->arrayQuery($query);

        if (count($result) == 1 and !$returnlist)
        {
            return $result[0];
        }
        else
        {
            return $result;
        }

    }

    public static function fetchByObjectId($objectId,$lang = null,$asObject = false,$returnlist = false)
    {
        $cond = array('contentobject_id'=>$objectId);
        $object = self::fetch(self::definition(),$cond,null,null,null,true,false);
        if ($object instanceof lcContentMenu)
        {
            return $object;
        }
        else
        {
            return false;
        }
    }

    public static function fetchAll($asObject = true)
    {
        return self::fetch(self::definition(),null,null,null,null,$asObject,true);
    }

    /*!

    Add a new node
    \param integer $parent
    \param string $name
    \param integer $contentobject_id
    \param string $lang
    */
    public static function addMenuTo($parent,$name,$contentobject_id,$lang)
    {
        $newMenu = lcMenu::addTo($parent);
        $rowNewContentArray = array('name' 				=> $name,
								    'contentobject_id' 	=> $contentobject_id,
								    'node_id' 			=> $newMenu->attribute('node_id'),
									'lang' 				=> $lang);
        $parentPathString = "";
        if ($parent > 0)
        {
            $parentContentMenu = lcContentMenu::fetchByNodeId($parent, $lang,false);
            if (isset($parentContentMenu['path_string']))
            {
                $parentPathString = $parentContentMenu['path_string'];
            }

        }

        $newContentMenu = new lcContentMenu($rowNewContentArray);
        $cleanName = $newContentMenu->makeNormName($name);
        $newPath = self::buildUrlAlias($parentPathString."/".$cleanName);
        $newContentMenu->setAttribute('path_string', $newPath);
        $newContentMenu->store();
        return $newContentMenu;
    }

    /*!
     * build the url alias based on the given path
     * this method adds an integer at the end of the path like name_1
     * \param string $path
     * \return string
     */
    public static function buildUrlAlias($path)
    {
        $index = 1;
        $searchedURl = $path;
        while (self::aliasExists($searchedURl))
        {
            $searchedURl = $path . "_".$index;
            $index++;
        }
        return $searchedURl;
    }

    /*!
     * Search if the given path exists as an url alias
     * \param string $path
     * \return boolean
     */
    public static function aliasExists($path)
    {
        $query = "SELECT count(*) as nb FROM contentmenu WHERE path_string = '$path'";
        $db = lcDB::getInstance();
        $res = $db->arrayQuery($query);
        if ($res[0]['nb'] > 0)
            return true;
        else
            return false;
    }

    /*
     * return the current menu section id
     * \return integer
     */
    public function section()
    {
        $menu = lcMenu::fetchById($this->attribute('node_id'),false);
        return $menu['section_id'];

    }
    
    public function updateName($name)
    {
        $this->setAttribute('name', $name);
        
        /*$cleanName = $this->makeNormName($name);
        $oldPath = explode('/',$this->attribute('path_string'));
        $nb = count($oldPath);
        $oldPath[$nb - 1] =  $cleanName;
        $newPath = implode('/',$oldPath);
        $this->setAttribute('path_string', $newPath);*/
       $this->store();
       $this->updatePaths($this->node_id);
        
        
    }

    /*!

    Update a menu entry
    \param integer $parent
    \param string $name
    \param integer $contentobject_id
    \param string $lang
    */
    public function updateMenu($parent,$name,$contentobject_id,$lang)
    {
        $this->setAttribute('name', $name);
        $this->store();
        $lcMenu = lcMenu::fetchById($this->node_id);
        if ($lcMenu->attribute("parent_node_id") != $parent)
        {
            // update content menu path_id according to new parent
            // update childrens path_ids according to the new position of thiere parent
            $lcMenu->setNewParent($parent);
            $this->setAttribute('name', $name);
            // as the parent node id has change, we have to update all the paths strings for all
            // languages
            self::updatePaths($this->node_id);

            //self::updateAllChildrenPathStrings($this->node_id);
        }
        else
        {
            // if the parent hasn't changed we just have to update the path_strings for the childrens
            // matching the current contentmenu language
            $this->updateChildrenNames();
        }
    }

    /*!

    update the path string
    \param integer $node_id
    */
    public static function updatePaths($node_id)
    {
        $nodes = self::fetchByNodeId($node_id,null,true,true);

        $db = lcDB::getInstance();
        $db->begin();
        foreach ($nodes as $node)
        {
            $parent = $node->parent();
            $parentPathString = "";
            if ($parent)
            {
                $parentPathString = $parent->attribute('path_string');
            }
            $oldPathString = $node->attribute('path_string');
            $newPathString = $parentPathString."/".$node->makeNormName($node->attribute('name'));
            $node->setAttribute('path_string',$newPathString);
            $node->store();

            $query = "SELECT * from contentmenu WHERE path_string like '$oldPathString%' and node_id !=".$node->attribute('node_id');
            $pathLenght = strlen($oldPathString);
            $childrens = $db->arrayQuery($query);
            foreach ($childrens as $children)
            {

                $endPathString = substr($children['path_string'], $pathLenght);
                $newChildrenPathString = $newPathString.$endPathString;
                $query = "UPDATE contentmenu SET path_string='$newChildrenPathString' WHERE id=".$children['id'];
                $db->query($query);
            }

        }
        $db->commit();
    }

    /*!

    Fetch a node parent
    \param boolean $allversions
    */
    public function parent($allversions = false)
    {
        $db = lcDB::getInstance();
        $languageCond = "";
        if (!$allversions)
        {
            $languageCond = "AND contentmenu.lang = '".$this->lang."'";
        }
        $query ="SELECT contentmenu.* FROM contentmenu, menu ".
				"WHERE menu.node_id = ".$this->node_id ." ".
				"AND contentmenu.node_id = menu.parent_node_id ".
        $languageCond;
        $result = $db->arrayQuery($query);
        if (count($result) == 1)
        {
            return new lcContentMenu($result[0]);
        }
        else
        {
            return false;
        }

    }

    /*!
     fecth the cotentmenu children of the current node
     \param boolean $allversions
     */
    public function children($allversions = false)
    {
        $db = lcDB::getInstance();
        $languageCond = "";
        if (!$allversions)
        {
            $languageCond = "AND contentmenu.lang = '".$this->lang."'";
        }
        $query = "SELECT contentmenu.* FROM contentmenu, menu ".
	             "WHERE menu.parent_node_id ='".$this->node_id."' ".
	             "ORDER_BY menu.sort_val ".
        $languageCond;
        $result = $db->arrayQuery($query);
        if (count($result) == 1)
        {
            return new lcContentMenu($result[0]);
        }
        else
        {
            return false;
        }

    }
    
    public function MoveToNode($newParentNodeId)
    {
        $db = lcDB::getInstance();
        $db->begin();
        
        // fetch parent content menu
        $parentContentMenu = self::fetchByNodeId($newParentNodeId);
        $pathString = $parentContentMenu->attribute('path_string');
        // set new path_string value
        $currentPathArray = array_reverse(explode('/',$this->attribute('path_string')));
        $currentNodeId = $this->attribute('node_id');
        $newPath_string = $pathString."/".$currentPathArray[0];
        $this->setAttribute('path_string', $newPath_string);
        $this->store();
        
        // update menu path_ids, depth and parent
        $currentMenu = lcMenu::fetchById($this->attribute('node_id'));
        $parentMenu = lcMenu::fetchById($newParentNodeId);
        $parentPathIds = $parentMenu->attribute('path_ids');
        $parentDepth = $parentMenu->attribute('depth');
        $newPathIds = $parentPathIds.$currentNodeId."/";
        $newDepth = $parentDepth + 1;
        
        $currentMenu->setAttribute('path_ids',$newPathIds);
        $currentMenu->setAttribute('depth',$newDepth);
        $currentMenu->setAttribute('parent_node_id',$newParentNodeId);
        $currentMenu->store();
        
        self::recursiveUpdateChildrens($currentNodeId);
         
        $db->commit();
    }
    
    public static function recursiveUpdateChildrens($nodeId)
    {
        if (lcMenu::hasChildren($nodeId))
        {
            $childrens = lcMenu::fetchChildrens($nodeId);
            
            $parentNodeMenu = lcMenu::fetchById($nodeId);
            $parentContentMenu = lcContentMenu::fetchByNodeId($nodeId);
            $parentPathString = $parentContentMenu->attribute('path_string');
            $parentContentMenu = lcContentMenu::fetchByNodeId($nodeId);
            $parentPathIds = $parentNodeMenu->attribute('path_ids');
            $parentDepth = $parentNodeMenu->attribute('depth');
            
            foreach ($childrens as $childMenu)
            {
                $childNode_id = $childMenu->attribute('node_id');
                $newChildPath_ids = $parentPathIds.$childNode_id."/";
                $newChildDepth = $parentDepth+1;
                $childMenu->setAttribute('path_ids',$newChildPath_ids);
                $childMenu->setAttribute('depth',$newChildDepth);
                $childMenu->store();
                
                $childContentMenu = self::fetchByNodeId($childNode_id);
                $childPathArray = array_reverse(explode('/',$childContentMenu->attribute('path_string')));
                $childPath_string = $parentPathString."/".$childPathArray[0];
                $childContentMenu->setAttribute('path_string', $childPath_string);
                $childContentMenu->store();
                
                if (lcMenu::hasChildren($childNode_id))
                {
                    self::recursiveUpdateChildrens($childNode_id);
                }
                
            }
        }
    }

    public static function hasSection($section_id,$nodeId)
    {
        $count = self::fetchCount(lcMenu::definition(),
        array('section_id' => $section_id,
                          			    'node_id' => $nodeId)
        );
        return ($count > 0)?true:false;
    }




    protected $id;
    protected $node_id;
    protected $contentobject_id;
    protected $path_string;
    protected $name;
    protected $lang;

}