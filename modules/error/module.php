<?php

$moduleConf = array('name' => 'Error',
					'defaultView' => 'display');
$viewList['display']= array('script'=>'display.php',
                            'function' => 'view',
							'ordered_params' => array("ErrorID","RedirectUrl"));

$functionList = array();
$functionList['view'] = array();

?>