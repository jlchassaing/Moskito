<?php

$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$Module = $Params['Module'];
$errorMsg = "";
if ($http->hasPostVariable("SaveButton"))
{
    if ($http->hasPostVariable("RoleNameValue"))
    {
        $newRoleName = $http->postVariable("RoleNameValue");
        if (!lcRole::roleExists($newRoleName))
        {
            $newRole = lcRole::addRole($newRoleName);
            $newRole->store();
        }
        else
        {
            lcDebug::write("Error", "A role already existe with the name : $newRoleName");
            $errorMsg = "A role already existe with the name : $newRoleName";
        }

    }
    else
    {
        lcDebug::write("Error", "No role Id was passed");
        $errorMsg = "No Role Id was specified";
    }
    if ($errorMsg == "")
    {
       $Module->redirectToModule('role','list');
    }
    else
    {
         $tpl->setVariable("error", $errorMsg);
         $Result['content'] = $tpl->fetch("roles/edit.tpl.php");
    }
}
else
{


    $Result['content'] = $tpl->fetch("roles/edit.tpl.php");

}




?>