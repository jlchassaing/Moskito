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
$viewList['edit']= array('script'=>'edit.php',
                           'function' => 'edit',
                          'ordered_params'=>array("UserId"));
$viewList['delete']= array('script'=>'delete.php',
                           'function' => 'delete',
                          'ordered_params'=>array("UserId"));

$functionList = array();
$functionList['login'] = array();
$functionList['edit'] = array();
$functionList['create'] = array();
$functionList['delete'] = array();


?>