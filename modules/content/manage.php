<?php

$tpl = new lcTemplate();

$contentList = lcContentObject::fetch(lcContentObject::definition(),null,null,null,null,true,true);

$settings = lcSettings::getInstance();
$currentLanguage = $settings->value('lang','current');
$availableLanguages = $settings->value('lang','available');
$contentClassIdentifiers = lcContentClass::getAvailableContentClasses();

$tpl->setVariable("language", $currentLanguage);
$tpl->setVariable("languageList", $availableLanguages);
$tpl->setVariable("classes", $contentClassIdentifiers);
$tpl->setVariable("contents", $contentList);
$Result['content'] = $tpl->fetch("content/manage.tpl.php");



?>