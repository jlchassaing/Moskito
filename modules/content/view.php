<?php

$tpl = new lcTemplate();

$viewMode = (isset($Params['View']))?$Params['View']:false;
$nodeID = (isset($Params['NodeId']))?$Params['NodeId']:false;
$lang = (isset($Params['Lang']))?$Params['Lang']:false;

$settings = lcSettings::getInstance();

if (!$lang)
{
	$lang = $settings->value("lang","current");
}
$tplPath = "default.tpl.php";

if ($viewMode AND $nodeID)
{
	$contentObject = lcContentObject::fetchByNodeId((int) $nodeID,$lang);
	if ($contentObject instanceof lcContentObject)
	{
		$classIdentifier = $contentObject->attribute('class_identifier');
		$templateRule = lcTemplateRule::getInstance();
		$rulesSet = array('class'=>$classIdentifier,
					  'NodeId' => $nodeID);
		$tplPath = $templateRule->getTemplate($rulesSet);
		$tpl->setVariable("node_id",$nodeID);
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