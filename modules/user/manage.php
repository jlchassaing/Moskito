<?php

$tpl = new lcTemplate();

$usersList = lcUser::getUsers();

$tpl->setVariable("users", $usersList);

$Result['content'] = $tpl->fetch('users/manage.tpl.php');



?>