<?php

$moduleConf = array('name' => 'Sections',
					'defaultView' => 'list');
$viewList['list']= array('script'=>'list.php',
                           'function' => 'edit');
$viewList['action']= array('script'=>'action.php',
                         'function' => 'edit');
$viewList['add']= array('script'=>'add.php',
                         'function' => 'edit');
$viewList['edit']= array('script'=>'edit.php',
                         'function' => 'edit');
$viewList['remove']= array('script'=>'remove.php',
						   'ordered_params'=> array('SectionID'),
                           'function' => 'edit');
$viewList['assign']= array('script'=>'assign.php',
						   'ordered_params'=> array('SectionID'),
                           'function' => 'edit');
$viewList['set']= array('script'=>'assign.php',
						   'ordered_params'=> array('SectionID'),
                           'function' => 'edit');



$functionList = array();
$functionList['edit'] = array();



?>