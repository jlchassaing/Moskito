<?php

if (isset($_SESSION['user_id']))
{
	unset($_SESSION['user_id']);
	
}
$redirUri = lcHTTPTool::buildUrl('/');
lcHTTPTool::redirect($redirUri);


?>