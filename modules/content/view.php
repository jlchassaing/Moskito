<?php

$tpl = new lcTemplate();

$viewMode = (isset($Params['View']))?$Params['View']:false;
$nodeID = (isset($Params['NodeId']))?$Params['NodeId']:false;
$lang = (isset($Params['Lang']))?$Params['Lang']:false;

$Module = $Params['Module'];

$Node = null;
if (isset($Params['Node']))
{
    $Node = $Params['Node'];
    $nodeID = $Node->attribute('node_id');
}

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
		$rulesSet = array('Match' => array('Class'    => $classIdentifier,
					  	  				   'NodeId'   => $nodeID),
		                  'Action'   => 'content/view/'.$viewMode.'.tpl.php');


		$cache = lcCache::getInstance();
		if (($content = $cache->hasValidCacheFile($Params)) !== false)
		{
		    $result['content'] =  $content;
		}
		else
		{
    		$tplPath = $templateRule->getTemplate($rulesSet);

            if ($tplPath == "")
                $tplPath = "content/view.tpl.php";
            $tpl->setVariable("node_id",$nodeID);
            $tpl->setVariable("object", $contentObject);
            if (isset($tplPath))
            {
                $Result['content'] = $tpl->fetch($tplPath);
            }
            $cache->makeCacheFile($Params, $Result['content']);
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