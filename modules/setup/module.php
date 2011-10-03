<?php

$moduleConf = array('name' => 'Setup',
					'defaultView' => 'step');
$viewList['step']= array('script'=>'step.php',
                           'function' => 'install');

$viewList['upgrade']= array('script'=>'upgrade.php',
                           'function' => 'install');

$viewList['run']= array('script'=>'run.php',
                        'function' => 'install',
                        'ordered_params'=>array('FileID',"FileHash"),);

$functionList = array();
$functionList['install'] = array();



?>