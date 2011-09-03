<?php

$tpl = new lcTemplate();

$viewMode = (isset($Params['View']))?$Params['View']:false;
$objectID = (isset($Params['ObjectId']))?$Params['ObjectId']:false;
$lang = (isset($Params['Lang']))?$Params['Lang']:false;

$settings = lcSettings::getInstance();

if (!$lang)
{
	$lang = $settings->value("lang","current");
}
$tplPath = "default.tpl.php";

if ($viewMode AND $objectID)
{
	$contentObject = lcContentObject::fetchById($objectID,$lang);
	if ($contentObject instanceof lcContentObject)
	{
		$classIdentifier = $contentObject->attribute('class_identifier');
		$templateRule = lcTemplateRule::getInstance();
		$rulesSet = array('Class'    => $classIdentifier,
					  	  'NodeId'   => null,
		                  'Action'   => 'content/view.tpl.php');
		$tplPath = $templateRule->getTemplate($rulesSet);

		if ($tplPath == "")
		    $tplPath = "content/view.tpl.php";
		$tpl->setVariable("node_id",null);
		$tpl->setVariable("object", $contentObject);
		if (isset($tplPath))
		{
			$Result['content'] = $tpl->fetch($tplPath);
		}
	}
	else
	{
		$Result['content'] = $tpl->fetch($tplPath);
	}

}
else
{
	$Result['content'] = $tpl->fetch($tplPath);
}



?>