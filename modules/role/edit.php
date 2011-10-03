<?php

$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$Module = $Params['Module'];
$RoleID = isset($Params['RoleId'])?$Params['RoleId']:false;

$errorMsg = "";


if ($http->hasPostVariable("SaveButton"))
{
    if ($http->hasPostVariable("RoleNameValue"))
    {
        $newRoleName = $http->postVariable("RoleNameValue");
        if (lcRole::roleExists($newRoleName))
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
    if ($http->hasPostVariable("RoleIdValue"))
    {
        $editedRoleId = $http->postVariable("RoleIdValue");
        $editedRole = lcRole::fetchById($editedRoleId);
        if (!($editedRole instanceOf lcRole))
        {
            lcDebug::write("Error", "Could not find a role with the specified id");
            $errorMsg = "Could not find a role with the specified id";
        }
    }
    else
    {
        lcDebug::write("Error", "No role Id was passed");
        $errorMsg = "No Role Id was specified";
    }

    if ($errorMsg == "")
    {

        $editedRole->setAttribute('name', $newRoleName);
        $editedRole->store();
       $Module->redirectToModule('role','list');
    }
    else
    {
         $tpl->setVariable("error", $errorMsg);
         $tpl->setVariable("role",$editedRole);
         $Result['content'] = $tpl->fetch("roles/edit.tpl.php");
    }
}
elseif($http->hasPostVariable("AddNewButton"))
{
    if ($http->hasPostVariable("RoleNameValue"))
    {
        $newRoleName = $http->postVariable("RoleNameValue");
        if (lcRole::roleExists($newRoleName))
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

          $newRole = lcRole::addRole($newRoleName);
          $newRole->store();
        $Module->redirectToModule('role','list');
    }


}
else
{
    if ($RoleID)
    {
        $roleToEdit = lcRole::fetchById($RoleID);
        $tpl->setVariable("role", $roleToEdit);
        $Result['content'] = $tpl->fetch("roles/edit.tpl.php");
    }
    else
    $Result['content'] = $tpl->fetch("roles/edit.tpl.php");

}




?>