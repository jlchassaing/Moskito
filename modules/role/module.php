<?php

$moduleConf = array('name' => 'Test Module',
					'defaultView' => 'list');

$viewList['action']= array('script'=>'action.php',
                           'function' => 'edit');
$viewList['list']= array('script'=>'role.php',
                         'function' => 'edit');
$viewList['add']= array('script'=>'add.php',
                         'function' => 'edit');
$viewList['view']= array('script'=>'role.php',
						 'ordered_params'=>array('RoleId'),
                         'function' => 'edit');
$viewList['rule']= array('script'=>'rule.php',
						 'ordered_params'=>array('RuleId'),
                         'function' => 'edit');

$functionList = array();
$functionList['edit'] = array();

?>