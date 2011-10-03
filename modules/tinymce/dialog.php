<?php
$tpl = new lcTemplate();
$ViewType = (isset($Params['Type']))?$Params['Type']:null;
$contentList = null;
if ($ViewType == 'image')
{

    $contentList = lcContentObject::fetch(lcContentObject::definition(),
                                          array('class_identifier' => 'image'),
                                          null,
                                          array('created' => true),
                                          null,
                                          true);
}

$tpl->setVariable('content', $contentList);
$Result['layout'] = 'dialoglayout.tpl.php';
$Result['content'] = $tpl->fetch("tinymce/browser.tpl.php");


?>
