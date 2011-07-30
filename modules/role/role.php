<?php

$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$Module = $Params['Module'];
$errorMsg = "";
if ($Module->isCurrentView("view"))
{
    if (isset($Params['RoleId']))
    {
        $roleId = $Params['RoleId'];
        $roleItem = lcRole::fetchById($roleId);
    }
    else
    {
        lcDebug::write("Error", "No role Id was passed");
        $errorMsg = "No Role Id was specified";
    }
    if ($errorMsg == "")
    {
        $tpl->setVariable("role", $roleItem);
         $Result['content'] = $tpl->fetch("roles/view.tpl.php");
    }
}
else
{
    $roleList = lcRole::fetch(lcRole::definition(),null,true,null,true);

    $tpl->setVariable("roles", $roleList);

    $Result['content'] = $tpl->fetch("roles/list.tpl.php");

}




?>