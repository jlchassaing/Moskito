<?php

$tpl = new lcTemplate();

$db = lcDB::getInstance();
$settings = lcSettings::getInstance();

$firstNodeId = $settings->value('TreeNodes','ContentNode');
$lang = $settings->value('lang','current');

$contentTree = lcContentMenu::fetchMenuTree($firstNodeId,$lang);

$tpl->setVariable("contentTree", $contentTree);

$content = $tpl->fetch("content/googlesitemap.tpl.php");

header('Content-Type:text/xml; charset=utf-8');
echo $content;

$out = ob_get_clean();
echo trim($out);

exit(0);

?>