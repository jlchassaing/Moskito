<?php

$Module = $Params['Module'];
$http= lcHTTPTool::getInstance();

if ($http->hasPostVariable("CreateUserButton"))
{
    $Module->redirectToModule('user','edit');
}



?>