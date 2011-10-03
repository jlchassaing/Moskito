<?php


$http = lcHTTPTool::getInstance();
$tpl = new lcTemplate();
$settings = lcSettings::getInstance();
$Module = $Params['Module'];

if ($http->hasPostVariable("EditButton"))
{
	// editing content
	if ($http->hasPostVariable("ContentObjectIDValue"))
	{
		$objectID = $http->postVariable("ContentObjectIDValue");

		if ($http->hasPostVariable("ContentLanguageValue"))
		{
			$lang = $http->postVariable("ContentLanguageValue");
		}
		else
		{
			$lang = $settings->value('lang', 'current');
		}

		$Module->redirectToModule('content','edit',array('ObjectId' => $objectID,'Lang'=>$lang));

	}

}
elseif($http->hasPostVariable("CreateContentButton"))
{
	$classId = "";
	if ($http->hasPostVariable("ContentClassIdentifier"))
	{
		$classId = $http->postVariable("ContentClassIdentifier");
	}
	$langId = "";
	if ($http->hasPostVariable("contentLanguageValue"))
	{
		$langId = $http->postVariable("contentLanguageValue");
	}
	$params = array('ClassId'=>$classId,'Lang'=>$langId);
	$Module->redirectToModule('content','create',$params);
}
elseif($http->hasPostVariable("RemoveButton"))
{
	if ($http->hasPostVariable("ContentObjectIDValue"))
	{
		$objectID = $http->postVariable("ContentObjectIDValue");

		$menuList = lcContentMenu::fetchMenuByObjectId($objectID,null,true,true);
		foreach ($menuList as $menu)
		{
			$object = lcContentObject::fetchByNodeId($menu['node_id'],$menu['lang'],true);
			$object->removeObject();
			if (!lcMenu::hasChildren($menu['node_id']))
			{
				lcContentMenu::removeMenu($menu['node_id']);
			}
		}

	}
	$Module->redirectToModule('content','manage');
}



?>