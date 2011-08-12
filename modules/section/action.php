<?php

$Module = $Params['Module'];

$http = lcHTTPTool::getInstance();



if ($http->hasPostVariable("AddSectionButton"))
{

    $Module->redirectToModule('section','add');


}