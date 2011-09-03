<?php
$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$settings = lcSettings::getInstance();
$Module = $Params['Module'];
$isError = false;
$errorMsg = "";
if ($http->hasPostVariable("CreateButton"))
{
	if ($http->hasPostVariable("ClassId"))
	{
		$classid = $http->postVariable("ClassId");
	}
	else
	{
		lcDebug::write("ERROR", "No class Identifier to create content");
		$isError = true;
		$errorMsg = "No class Identifier to create content";
	}
	if ($http->hasPostVariable("LanguageValue"))
	{
		$lang = $http->postVariable("LanguageValue");
	}
	else
	{
		$lang = $GLOBALS['SETTINGS']['currentLanguage'];
	}

	if (!$isError)
	{

		$data = array('class_identifier'=>$classid,'lang'=>$lang);

		$newObject = new lcContentObject($data);

		foreach ($newObject->dataMap() as $attribute)
		{
			$dataType = $attribute->datatype();
			if ($dataType->canGetFromHttp($http, $attribute))
			{
				$dataType->getFromHttp($http, $attribute);
			}
		}
		$newObject->store();
        if ($http->hasPostVariable("AddToAMenu") and $http->hasPostVariable("AddToAMenu") == "1" )
        {
            if ($http->hasPostVariable("MenuParentValue"))
            {
                $parentId = $http->postVariable("MenuParentValue");

                $menuName = $newObject->attribute('object_name');

                $NewMenu = lcContentMenu::addMenuTo($parentId,$menuName,$newObject->attribute('id'),$lang);
            }
        }

		$Module->redirectToModule('content', 'manage');
	}
}
else
{
	$classID = $Params['ClassId'];
	$settings = lcSettings::getInstance();

	$lang = (!isset($Params['Lang']) or $Params['Lang']== "")?$settings->value('lang','current'): $Params['Lang'];


	$contentClass = lcContentClass::loadClassDefinition($classID);
	$fullMenu = lcContentMenu::fetchMenuTree(1,null,null,true);

	$tpl->setVariable("class", $contentClass);

	$tpl->setVariable("class_id", $classID);
	$tpl->setVariable("lang", $lang);
	$tpl->setVariable("fullMenu", $fullMenu);


	// ajouter lang au formulaire de création

	$Result['content'] = $tpl->fetch("content/create.tpl.php");

}







?>