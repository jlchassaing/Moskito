<?php

/*!
  \class lcContentNodeObjectHandler lccontentnodeobjecthandler.php
  \version 0.1
  \author Jean-Luc Chassaing

  Handles content object in their contentmenu structure
 */
class lcContentNodeObjectHandler
{

    public static function fetchChildrens($node_id,$class_filter = null ,$sorting = null, $limit=null,$offset = null,$depth = null, $language= null)
    {
        $settings = lcSettings::getInstance();
        $language = ($language === null)?$settings->value('lang', 'current'):$language;


        $db = lcDB::getInstance();

        $classFilterCond = "";
        $limitCond       = "";
        $parentNodeCond  = "";
        $sortingCond     = "";

        if ($depth === null)
        {
            $parentNodeCond = "menu.parent_node_id = $node_id ";
        }
        elseif (is_int($depth) AND $depth > 0)
        {
            $parentmenu = lcMenu::fetchById($node_id);
            $parentSortValLength = strlen($parentmenu->attribute('sort_val'));
            $depthLength = $depth * 3;
            $sortValLenght = $parentSortValLength + $depthLength;
        	$parentNodeCond = "menu.path_ids like '".$parentmenu->attribute('path_ids')."%' ".
        	                  "AND CHAR_LENGTH(menu.sort_val) <= $sortValLenght ";
        }

        $query    = "SELECT contentobjects.id,contentobjects.object_name,contentobjects.created,contentobjects.updated,contentobjects.class_identifier,contentmenu.node_id ".
                 	"FROM contentmenu, menu, contentobjects ";

        $whereConditions = $parentNodeCond.
                           "AND contentmenu.node_id = menu.node_id ".
                           "AND contentmenu.lang = '$language' ".
                           "AND contentobjects.id = contentmenu.contentobject_id ";


        if (is_array($class_filter))
        {
            if (count($class_filter) == 1)
            {
                $classFilterCond = "AND contentobjects.class_identifier = '".$class_filter[0]."' ";
            }
            elseif (count($class_filter) > 1)
            {
                $classFilterCond = "AND contentobjects.class_identifier in ('".implode("','",$class_filter)."') ";
            }

        }

        if (is_array($sorting))
        {
           switch ($sorting[0])
           {
               case 'priority':
                $sortingCond = " ORDER BY menu.sort_val ";
               break;

               default:
                   $sortingCond = " ORDER BY contentobjects.".$sorting[0];
               break;
           }

           if (isset($sorting[1]) AND !$sorting[1])
           {
               $sortingCond = $sortingCond ." DESC";
           }
        }

        if (is_integer($limit))
        {
            $limitCond = " LIMIT ";
            if (is_integer($offset))
            {
                $limitCond = $limitCond."$offset,$limit ";
            }
            else
            {
                 $limitCond = $limitCond. " ".$limit ." ";
            }
        }

        $query = $query ."WHERE ".$whereConditions . $classFilterCond .$sortingCond. $limitCond;
        $result = $db->arrayQuery($query);
        $returnResult = array();
        if (count($result) > 0)
        {
            foreach ($result  as $objectRow)
            {
                $returnResult[] = new lcContentObject($objectRow);
            }
        }
        return $returnResult;
    }

    public static function fetchCountChildrens($parent_node_id,$class_filter = null, $language = null)
    {
        $classFilterCond = "";
        $settings = lcSettings::getInstance();
         $language = ($language === null)?$settings->value('lang', 'current'):$language;

        if (is_array($class_filter))
        {
            if (count($class_filter) == 1)
            {
                $classFilterCond = "AND contentobjects.class_identifier = '".$class_filter[0]."' ";
            }
            elseif (count($class_filter) > 1)
            {
                $class_filter = "AND contentojbects.class_identifier in ('".implode("','",$class_filter)."') ";
            }
        }

        $query = "SELECT count(menu.node_id) as cte ";
        $QueryFROM = "FROM contentmenu, menu, contentobjects ";
        $QueryWheres = "WHERE menu.parent_node_id = $parent_node_id
                           AND contentmenu.node_id = menu.node_id
                           AND contentmenu.lang = '$language'
                           AND contentobjects.id = contentmenu.contentobject_id ";

        $db = lcDB::getInstance();
        $query = $query . $QueryFROM . $QueryWheres;
        $res = $db->arrayQuery($query);

        return $res[0]['cte'];
    }

    public static function removeObject($objectId)
    {
        $menuList = lcContentMenu::fetchMenuByObjectId($objectId,null,true,true);
        $childrens = lcContentNodeObjectHandler::fetchChildrens($menuList['node_id']);
        foreach ($menuList as $menu)
        {
            $object = lcContentObject::fetchByNodeId($menu['node_id'],$menu['lang'],true);

            $object->removeObject();
            lcContentMenu::removeMenu($menu['node_id']);

        }
        foreach ($childrens as $item)
        {
            self::removeObject($item->attribute('id'));
        }
    }

}