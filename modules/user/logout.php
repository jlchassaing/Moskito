<?php


lcSession::resetValue('user_id');
lcSession::stop();
$redirUri = lcHTTPTool::buildUrl('/');
lcHTTPTool::redirect($redirUri);


?>