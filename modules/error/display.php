<?php
$tpl = new lcTemplate();

$ErrorId = $Params['ErrorID'];
if (isset($Params['RedirectUrl']))
{
    $tpl->setVariable("redirect_to", $Params['RedirectUrl']);
}


$Result['content'] = $tpl->fetch('error/'.$ErrorId.".tpl.php");