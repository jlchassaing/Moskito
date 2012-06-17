<?php


$http = lcHTTPTool::getInstance();
$tpl = new lcTemplate();

$objectID = (isset($Params['ObjectId']))?$Params['ObjectId']:null;
$lang = (isset($Params['Lang']))?$Params['Lang']:null;
$Module = $Params['Module'];

if ($http->hasPostVariable("ContentNodeId"))
{
    $NodeId = $http->postVariable("ContentNodeId");
}

if ($http->hasPostVariable("SaveButton"))
{
	// editing content
	if ($http->hasPostVariable("ObjectIdValue"))
	{
		$objectID = $http->postVariable("ObjectIdValue");

		if ($http->hasPostVariable("ContentLanguageValue"))
		{
			$lang = $http->postVariable("ContentLanguageValue");
		}
		else
		{
			$lang = $GLOBALS['SETTINGS']['currentLanguage'];
		}

		$contentObject = lcContentObject::fetchById($objectID,true);

		foreach ($contentObject->dataMap() as $attribute)
		{
			$dataType = $attribute->datatype();
			if ($dataType->canGetFromHttp($http, $attribute))
			{
				$dataType->getFromHttp($http, $attribute);
			}
		}
		$contentObject->store();

		if ($http->hasPostVariable("ContentMenuIdValue"))
		{
			$contentMenuId = $http->postVariable("ContentMenuIdValue");

			$currentContentMenu = lcContentMenu::fetchById($contentMenuId);
			$menuName = $contentObject->attribute('object_name');
			$currentContentMenu->updateName($menuName);
			
			
		}

		/*if ($http->hasPostVariable("MenuParentValue"))
		{
			$parentId = $http->postVariable("MenuParentValue");
			$menuName = "";
			/*if ($http->hasPostVariable("MenuNameValue"))
			{
				$menuName = $http->postVariable("MenuNameValue");
			}
			if ($menuName == "")
			{
				$menuName = $contentObject->attribute('object_name');
			}
			if ($currentContentMenu instanceof lcContentMenu)
			{
				$currentContentMenu->updateMenu($parentId,$menuName,$contentObject->attribute('id'),$lang);
			}
			else
			{
				$NewMenu = lcContentMenu::addMenuTo($parentId,$menuName,$contentObject->attribute('id'),$lang);
			}

		}*/
	}
	$Module->redirectToModule('content','view',array('full',$NodeId));


}
else
{
    if ($http->hasPostVariable("EditButton"))
    {

        if ($http->hasPostVariable("ObjectIdValue"))
        {
            $objectID = $http->postVariable("ObjectIdValue");
        }
        if ($http->hasPostVariable("ContentLanguageValue"))
        {
            $lang = $http->postVariable("ContentLanguageValue");
        }
        else
        {
            $lang = null;
        }

    }

	$contentObject = lcContentObject::fetchById($objectID,$lang);
	if ($contentObject instanceof lcContentObject)
	{
		$contentMenu = lcContentMenu::fetchMenuByObjectId($objectID, $lang);

		$fullMenu = lcContentMenu::fetchMenuTree(0,null,null,true);
		$tpl->setVariable("object", $contentObject);
		$tpl->setVariable("lang", $lang);
		$tpl->setVariable("menu", $contentMenu);
		$tpl->setVariable("fullMenu", $fullMenu);
	}
	else
	{
		lcDebug::write("ERROR", "There is not contentobject matching id : $objectID");
	}

	$tplName = "content/edit.tpl.php";

}

$Result['content'] = $tpl->fetch($tplName);

?>