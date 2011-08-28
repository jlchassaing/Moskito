<?php

$tpl = new lcTemplate();

$Offset = (isset($Params['Offset']))?$Params['Offset']:0;
$NbElements = (isset($Params['Limit']))?$Params['Limit']:0;

$elementsPerPage = 5;
$NbElements = $elementsPerPage;

$contentList = lcContentObject::fetch(lcContentObject::definition(),null,null,null,array($Offset,$NbElements),true,true);
$nb = lcContentObject::fetchCount(lcContentObject::definition());

$settings = lcSettings::getInstance();
$currentLanguage = $settings->value('lang','current');
$availableLanguages = $settings->value('lang','available');
$contentClassIdentifiers = lcContentClass::getAvailableContentClasses();

$tpl->setVariable("language", $currentLanguage);
$tpl->setVariable("languageList", $availableLanguages);
$tpl->setVariable("classes", $contentClassIdentifiers);
$tpl->setVariable("contents", $contentList);
$tpl->setVariable("nb_elements", $nb);
$tpl->setVariable("offset" ,$Offset);
$tpl->setVariable("nbitems" ,$elementsPerPage);

$Result['content'] = $tpl->fetch("content/manage.tpl.php");



?>