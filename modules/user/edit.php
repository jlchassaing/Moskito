<?php

$Module = $Params['Module'];
$UserID = isset($Params['UserId'])?$Params['UserId']:false;
$http = lcHTTPTool::getInstance();

$tpl = new lcTemplate();


if ($http->hasPostVariable("SaveNewUserButton"))
{
    if (!$http->hasPostVariable("field_login"))
    {
        $error = "Login not specified";
    }
    else
    {
        $user['login'] = $http->postVariable("field_login");
    }

    if (!$http->hasPostVariable("field_pwd"))
    {
        $error = "Password not specified";
    }
    else
    {
        $user['password'] = $http->postVariable("field_pwd");
        if (!$http->hasPostVariable("field_pwd_check"))
        {
            $error = "Password not verified";
        }
        else
        {
            $verif = $http->postVariable("field_pwd_check");
            if ($verif !== $user['password'])
            {
                $error = "Password not verified";
            }
            else
            {
                if (!lcUser::checkPassword($user['password']))
                {
                    $error = "malformed Password";
                }

            }
        }
    }
    if ($http->hasPostVariable("RoleIdValue"))
    {
        $user['role'] = $http->postVariable("RoleIdValue");
    }
    if ($error != "")
    {
        $roleList = lcRole::getRoles();
        $tpl->setVariable('roles',$roleList);
        $tpl->setVariable('error',$error);
        $tpl->setVariable("user", $user);
        $Result['content'] = $tpl->fetch('users/edit.tpl.php');
    }
    else
    {
        $user['password'] = lcUser::encodePassword($user['login'], $user['password']);
        $newUser = new lcUser($user);
        $newUser->store();
        $Module->redirectToModule('user','manage');
    }

}
elseif ($http->hasPostVariable("SaveButton"))
{
    if (!$http->hasPostVariable("field_login"))
    {
        $error = "Login not specified";
    }
    else
    {
        $user['login'] = $http->postVariable("field_login");
    }

    if (!$http->hasPostVariable("field_pwd"))
    {
        $error = "Password not specified";
    }
    else
    {
        $user['password'] = $http->postVariable("field_pwd");
        if (!$http->hasPostVariable("field_pwd_check"))
        {
            $error = "Password not verified";
        }
        else
        {
            $verif = $http->postVariable("field_pwd_check");
            if ($verif !== $user['password'])
            {
                $error = "Password not verified";
            }
            else
            {
                if (!lcUser::checkPassword($user['password']))
                {
                    $error = "malformed Password";
                }

            }
        }
    }
    if ($http->hasPostVariable("RoleIdValue"))
    {
        $user['role'] = $http->postVariable("RoleIdValue");
    }

    if (!$http->hasPostVariable("UserIdValue"))
    {
        $error = "userId not specified";
    }
    else
    {
        $UserID = $http->postVariable("UserIdValue");
    }
    $editUser = lcUser::getById($UserID);
    $editUser->setAttribute('login',$user['login']);
    $editUser->setAttribute('role',$user['role']);
    if ($user['password'] != "")
    {
        $user['password'] = lcUser::encodePassword($user['login'], $user['password']);
        $editUser->setAttribute('role',$user['role']);
    }
    $editUser->store();
    $Module->redirectToModule('user','manage');


}
else
{
    if ($UserID)
    {
        $user = lcUser::getById($UserID,false);
        $tpl->setVariable('user',$user);
    }
    $roleList = lcRole::getRoles();
    $tpl->setVariable('roles',$roleList);
   $Result['content'] = $tpl->fetch('users/edit.tpl.php');
}
