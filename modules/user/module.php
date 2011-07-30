<?php

$moduleConf = array('name' => 'Test Module',
					'defaultView' => 'login');
$viewList['login']= array('script'=>'login.php',
                          'function' => 'login');
$viewList['logout']= array('script'=>'logout.php',
                           'function' => 'logout');
$viewList['manage']= array('script'=>'manage.php',
                           'function' => 'edit');
$viewList['action']= array('script'=>'action.php',
                           'function' => 'edit');
$viewList['role']= array('script'=>'role.php',
						 'ordered_params'=>array("Action",'RoleId'),
                           'function' => 'edit');
$viewList['rule']= array('script'=>'rule.php',
						 'ordered_params'=>array("Action",'RuleId'),
                           'function' => 'edit');

$functionList = array();
$functionList['login'] = array();
$functionList['edit'] = array();


?>