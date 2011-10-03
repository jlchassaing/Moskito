<?php

$Module = $Params['Module'];
$UserID = isset($Params['UserId'])?$Params['UserId']:false;
$http = lcHTTPTool::getInstance();

if ($UserID)
{
    $user = lcUser::getById($UserID);
    $user->delete();
}

$Module->redirectToModule('user','manage');

?>