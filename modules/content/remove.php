<?php

$Module = $Params['Module'];
$ObjectID = (isset($Params['ObjectId']))?$Params['ObjectId']:null;
$ObjLang = (isset($Params['Lang']))?$Params['Lang']:null;


$menuList = lcContentMenu::fetchByObjectId($ObjectID,null,true,true);
foreach ($menuList as $menu)
{
    $object = lcContentObject::fetchByNodeId($menu['node_id'],$menu['lang'],true);
    $object->removeObject();
    if (!lcMenu::hasChildren($menu['node_id']))
    {
        lcContentMenu::removeMenu($menu['node_id']);
    }
}


$Module->redirectToModule('content','manage');





?>