<?php
$tpl = new lcTemplate();

$ErrorId = $Params['ErrorID'];

$Result['content'] = $tpl->fetch('error/'.$ErrorId.".tpl.php");