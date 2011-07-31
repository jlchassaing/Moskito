<?php


$http = lcHTTPTool::getInstance();
$tpl = new lcTemplate();

$objectID = $Params['ObjectId'];
$lang = $Params['Lang'];
$Module = $Params['Module'];

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
		}
		
		if ($http->hasPostVariable("MenuParentValue"))
		{
			$parentId = $http->postVariable("MenuParentValue");
			$menuName = "";
			if ($http->hasPostVariable("MenuNameValue"))
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
			
		}
	}
	$Module->redirectToModule('content','manage');
	
	
}
else
{
	
	$contentObject = lcContentObject::fetchById($objectID,$lang);
	if ($contentObject instanceof lcContentObject)
	{
		$contentMenu = lcContentMenu::fetchByObjectId($objectID, $lang);
		
		$fullMenu = lcContentMenu::fetchMenuTree(1,null,null,true);
		$tpl->setVariable("object", $contentObject);
		$tpl->setVariable("lang", $lang);
		$tpl->setVariable("menu", $contentMenu);
		$tpl->setVariable("fullMenu", $fullMenu);
	}
	else 
	{
		lcDebug::write("ERROR", "There is not contentobject matching id : $objectID");
	}
	
	$tplName = "edit/edit.tpl.php";
		
}

$Result['content'] = $tpl->fetch($tplName);

?>