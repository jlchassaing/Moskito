<?php

$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$Module = $Params['Module'];
$RoleID = isset($Params['RoleId'])?$Params['RoleId']:false;

$errorMsg = "";



    if ($RoleID)
    {
        $roleToEdit = lcRole::fetchById($RoleID);
        $userlist = lcUser::getByRole($roleToEdit->attribute('name'));
        if (count($userlist) == 0)
        {
            $roleToEdit->delete();
        }
       // $tpl->setVariable("error", c);
       // $Result['content'] = $tpl->fetch("roles/list.tpl.php");
    }
   $Module->redirectToModule('role','list');







?>