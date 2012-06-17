<?php

$Module = $Params['Module'];
$ObjectID = (isset($Params['ObjectId']))?$Params['ObjectId']:null;
$ObjLang = (isset($Params['Lang']))?$Params['Lang']:null;
$http = lcHTTPTool::getInstance();

if ($http->hasPostVariable("ConfirmRemove"))
{
    if ($http->hasPostVariable("RemoveObjectId"))
    {
        $ObjectID = $http->postVariable("RemoveObjectId");
    }
    lcContentNodeObjectHandler::removeObject($ObjectID);


   /* $ObjectID = $http->postVariable("RemoveObjectId");
    $menuList = lcContentMenu::fetchMenuByObjectId($ObjectID,null,true,true);
    foreach ($menuList as $menu)
    {
        $object = lcContentObject::fetchByNodeId($menu['node_id'],$menu['lang'],true);
        $childrens = lcContentNodeObjectHandler::fetchChildrens($menu['node_id']);
        $object->removeObject();

        if (!lcMenu::hasChildren($menu['node_id']))
        {
            lcContentMenu::removeMenu($menu['node_id']);
        }
    }*/


    $Module->redirectToModule('content','view',array('View' => 'full','NodeId' => 1));

}
elseif($http->hasPostVariable("CancelRemove"))
{
    if ($http->hasPostVariable("RemoveObjectNodeId"))
    {
        $NodeId = $http->postVariable("RemoveObjectNodeId");
    }
    $Module->redirectToModule('content','view',array('View' => 'full','NodeId'=>$NodeId));
}
else
{
    $tpl = new lcTemplate();
    $obj = lcContentObject::fetchById($ObjectID);
    $menu = lcContentMenu::fetchByObjectId($obj->attribute('id'));
    $tpl->setVariable("object", $obj);
    $tpl->setVariable('node_id', $menu->attribute('node_id'));
    $Result['content'] = $tpl->fetch("content/remove_confirm.tpl.php");
}






?>