<?php

/*!

\class lcMenu lcmenu.php
\version 0.1

This class deals with the content menu tree
\author jlchassaing

*/
class lcMenu extends lcPersistent
{

    /*!

    Class Constructor
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

    static function definition()
    {
        return array('tableName' => 'menu',
                     'className' => 'lcMenu',
                     'fields'    => array('node_id'         => array('type' => 'integer'),
                                          'parent_node_id'  => array('type' => 'integer'),
                                          'sort_val'        => array('type' => 'integer'),
                                          'path_ids'        => array('type' => 'string'),
                                          'section_id'      => array('type' => 'integer'),
                                          'depth'           => array('type' => 'string')
        ),
                     'key' => 'node_id'
                     );
    }


    public static function hasChildren($node_id)
    {
        $db = lcDB::getInstance();
        $query = "SELECT count(*) as nb FROM menu WHERE parent_node_id = $node_id";
        $result = $db->arrayQuery($query);
        if (isset($result[0]['nb']) and $result[0]['nb'] > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }

    }


    public function fetchMenuById($node_id)
    {
        $db = lcDB::getInstance();
        $query = "SELECT * FROM ".$this->definition['tableName'].
                 " WHERE node_id = $node_id or parent_node_id=$node_id ".
                 "order_by parent_node";
    }

    /*!
     fetch the last Sort Value that has been set for a children of $parentNodeId
     if there are no children nodes then the method will return 0
     \param integer $parentNodeId
     \return integer
     */
    public static function fecthLastSortVal($parentNodeId)
    {
        $db = lcDB::getInstance();
        $query = "SELECT menu.sort_val FROM menu ".
                 "WHERE parent_node_id = $parentNodeId ".
                 "ORDER BY sort_val DESC ".
                 "LIMIT 0,1";
        $result = $db->arrayQuery($query);
        if (isset($result[0]['sort_val']) )
        {
            if ($result[0]['sort_val'] != 0)
            {
                return (int) substr($result[0]['sort_val'], -2);
            }
            else
            {
                return $result[0]['sort_val'];
            }

        }
        else
        {
            return null;
        }
    }

    /*!
     non static method to fetch the children's last sort value for the current parent Node.
     \return integer
     */
    public function lastSortVal()
    {
        return self::fecthLastSortVal($this->node_id);
    }

    /*!
     fetch a lcmenu content by node id as $menuId
     \return lcMenu
     */
    public static function fetchById($menuId, $asObject = true)
    {
        $cond = array('node_id'=>$menuId);
        return self::fetch(self::definition(),$cond,null,null,null,$asObject);
    }
    
    public static function fetchChildrens($node_id, $asObject = true)
    {
        $cond = array('parent_node_id' => $node_id);
        return self::fetch(self::definition(),$cond,null,null,null,$asObject);
    }


    public function getPathArray()
    {
        $temp = explode("/", substr($this->attribute("path_ids"),1,-1));
        return $temp;

    }

    public static function addTo($parent)
    {
        $newNodeData = array();
        $parentPathIds = "/";
        if ($parent > 0)
        {
            $parent = self::fetchById($parent);
            if ($parent instanceof lcMenu)
            {
                $newNodeData = array("parent_node_id" => $parent->attribute('node_id'),
                                     "section_id"     => $parent->attribute('section_id'));
                $parentPathIds = $parent->attribute("path_ids");

                $newNodeData["sort_val"] = self::getSortVal($parent);
            }

        }
        else
        {
            $settings = lcSettings::getInstance();
            if ($settings->hasValue("ContentSettings", "DefaultSection"))
            {
                $sectionId = $settings->value("ContentSettings", "DefaultSection");
            }
            else
            {
                $sectionId = lcSection::DEFAULT_SECTION_ID;
            }
            $newNodeData['parent_node_id'] = 0;
            $newNodeData['section_id'] = $sectionId;
            $newNodeData['sort_val'] = self::getSortVal();

        }

        $node = new self($newNodeData);
        $nodeId = $node->store();
        $node->setAttribute('node_id', $nodeId);
        $newPath = $parentPathIds . $nodeId ."/";
        $node->setAttribute("path_ids", $newPath);
        $node->setDepth();
        $node->store();
        return $node;

    }

    /*!
     build the new sort_val string for a menu item added to
     a parent.
     \param lcMenu $parent
     */
    public static function getSortVal($parent=null)
    {

        if ($parent !== null)
        {
            $lastSortValue = $parent->lastSortVal();
            $parentSortVal = $parent->attribute("sort_val");
        }
        else
        {
            $lastSortValue = self::fecthLastSortVal(0);
            $parentSortVal = "";
        }
        if ($lastSortValue === null)
        {
            $newSortValue = 0;
        }
        else
        {
            $newSortValue = $lastSortValue + 5;
        }

        if ($newSortValue < 10)
        {
            $newSortValue = "0$newSortValue";
        }
        if (is_int($parentSortVal) AND $parentSortVal == 0)
        {
            $parentSortVal = "00";
        }

        return ($parentSortVal != "")?$parentSortVal."/".$newSortValue:$newSortValue;
    }

    /*!
     * build the depth value based on the path_ids value
     * \param array $pathIds
     * \return integer
     */
    public static function getDepthFromPathIds($pathIds)
    {
        $t = explode("/",$pathIds);
        $depth = count($t) - 3;
        $depth_val = " depth = $depth";
        return $depth;
    }

    /*!
     * set the current depth
     */
    public function setDepth()
    {
        $pathIds = $this->attribute('path_ids');
        $depthValue = self::getDepthFromPathIds($pathIds);
        $this->setAttribute('depth', $depthValue);
    }


    public function setNewParent($parent)
    {
        // updating current node path_ids
        $parentPathId = "/";
        if ($parent != 0)
        {
            $newParent = self::fetchById($parent);
            $parentPathId = $newParent->attribute('path_ids');
            $section_id  = $newParent->attribute('section_id');
        }

        $oldPathId = $this->path_ids;
        $newPathId = $parentPathId.$this->node_id."/";
        $this->path_ids = $newPathId;
        $this->parent_node_id = $parent;
        $this->section_id = $section_id;
        $this->setDepth();

        // update sort val


        $this->sort_val = 0;

        $this->store();

        // updating children nodes path_ids
        $cond = array('path_ids' => "$oldPathId%");
        $childrenNodes = self::fetch(self::definition(),$cond,null,null,null,false,true);
        $db = lcDB::getInstance();
        $db->begin();
        $pathLenght = strlen($oldPathId);
       // $sortLength = strlen($oldSortVal);
        foreach ($childrenNodes as $children)
        {
            //$pathLenght = strlen($oldPathId);
            $pathLastIds = substr($children['path_ids'], $pathLenght);
            $newChildrenPath = $newPathId.$pathLastIds;
            /*

             $lastSortValue = substr($children['sort_val'], $sortLength);
            $newChildrenSortVal=$this->sort_val.$lastSortValue;*/
            $newDepth = self::getDepthFromPathIds($newChildrenPath);

            $query = "UPDATE ".$this->definition['tableName'] ." SET ".
                     "path_ids = '$newChildrenPath', depth= $newDepth, section_id=$section_id WHERE ".
                     "node_id = ".$children['node_id'];
            $db->query($query);
        }
        $db->commit();
    }

    public static function setSection($parentNodeId,$sectionId)
    {
        $db = lcDB::getInstance();

        $query = "UPDATE menu SET ".
                 "section_id = $sectionId  WHERE ".
                 "path_ids like '%/$parentNodeId/%'";

        $db->query($query);
    }



    protected $node_id;
    protected $parent_node_id;
    protected $name;
    protected $sort_val;
    protected $path_ids;
    protected $depth;



}