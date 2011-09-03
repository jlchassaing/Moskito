<?php
$moduleConf = array('name' => 'Object view',
					'defaultView' => 'view');

$viewList['view']= array('script'=>'view.php',
						 'ordered_params'=>array('View','ObjectId','Lang'),
                         'function' => 'read');


?>