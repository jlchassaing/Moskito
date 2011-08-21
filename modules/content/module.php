<?php

$moduleConf = array('name' => 'Test Module',
					'defaultView' => 'view');
$viewList['action']= array('script'=>'action.php',
                           'function' => 'edit');
$viewList['view']= array('script'=>'view.php',
						 'ordered_params'=>array('View','NodeId','Lang'),
                         'function' => 'read');
$viewList['create']= array('script'=>'create.php',
						   'ordered_params'=> array('ClassId','Lang'),
                           'function' => 'create');
$viewList['menu']= array('script'=>'menu.php',
						 'ordered_params'=>array('View',"MenuId"),
                         'function' =>'edit');
$viewList['edit']= array('script'=>'edit.php',
						 'ordered_params'=>array("ObjectId",'Lang'),
                         'function' => 'edit');
$viewList['manage']= array('script'=>'manage.php',
                           'function' => 'edit',
                           'ordered_params' => array('Offset', 'Limit'));
$viewList['googlesitemap']= array('script'=>'googlesitemap.php',
                                  'function' => 'read');
$viewList['select']= array('script'=>'select.php',
						 'ordered_params'=>array("NodeId"),
                         'function' => 'select');

$functionList = array();
$functionList['read'] = array();
$functionList['edit'] = array();
$functionList['create'] = array();



?>