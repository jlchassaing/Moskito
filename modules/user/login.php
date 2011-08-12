<?php
$http = lcHTTPTool::getInstance();


$userLogin = false;
$userPassword = false;

if ($http->hasPostVariable('LoginButton'))
{
	if ($http->hasPostVariable('login'))
	{
		$userLogin = lcDB::cleanData($http->postVariable('login'));
	}

	if ($http->hasPostVariable('password'))
	{
		$userPassword = lcDB::cleanData($http->postVariable('password'));
	}

	$db = lcDB::getInstance();
	$query = "SELECT * FROM users WHERE login ='$userLogin'";
	$aResult = $db->arrayQuery($query);
	$foundUser = false;

	if (is_array($aResult) AND count($aResult) > 0)
	{
		$checkPassword = lcUser::encodePassword($userLogin, $userPassword);
		if ($checkPassword == $aResult[0]["password"])
		{

			lcSession::setValue("user_id",$aResult[0]['id']);
			$foundUser = true;

		}
	}

	if ($foundUser)
	{
		if ($http->hasPostVariable('redirect'))
		{
			$redirect = $http->postVariable('redirect');
			$uri = $http->makeUrl($redirect);
			lcHTTPTool::redirect($uri);
		}
	}
	else
	{

		lcSession::setValue('last_request', $http->postVariable('redirect'));
		$uri= $http->makeUrl("user/login");
		lcHTTPTool::redirect($uri);
	}

}
else
{
	$tpl = new lcTemplate();
	$Result['layout'] = "userlayout.tpl.php";
	$Result['content'] = $tpl->fetch('user/login.tpl.php');
}



?>