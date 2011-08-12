<?php
/*!
  \mainpage Moskito Lite CMS

  \section intro_sec Introduction
 This is a powerfull tool the mange your content
  this project is built.

 */

ob_start();

$start = microtime();

$currentModule 	 = false;
$currentAccess 	 = false;
$currentDesign 	 = false;
$siteHost  	   	 = false;
$requestArray  	 = false;
$siteRootDir   	 = false;
$currentLanguage = false;

$settings = array();
$settings['currentModule'] = & $currentModule;
$settings['currentAccess'] = & $currentAccess;
$settings['currentDesign'] = & $currentDesign;
$settings['sitehost'] 	   = & $siteHost;
$settings['siteRootDir']   = & $siteRootDir;
$settings['currentLanguage']   = & $currentLanguage;

$GLOBALS['SETTINGS'] = & $settings;
$GLOBALS['RequestArray'] = & $requestArray;

include_once 'autoload.php';
$displayInstallMessage = false;
if (!Autoloader::registerAutoloaderFunctions())
{
     include_once 'kernel/setup/includes.php';
     $displayInstallMessage = true;
}


// Site Root Folder
$siteRootDir = dirname(__FILE__);

$http 	  = lcHTTPTool::getInstance();
$request  = $http->getRequest();
$siteHost = $request['host'];
$requestArray = $request['request'];

$accessManager = lcAccess::getInstance();
$access = $accessManager->getAccessFromRequestArray($requestArray);


if (!$access)
{
	$access = lcSettings::getValue("Default", "access", "settings/settings.ini");
}
else
{
	if (count($requestArray) > 0)
	{
		$requestArray = array_slice($requestArray, 1);
		$request['request'] = $requestArray;
		$request['url_alias'] = str_replace("/$access", "", $request['url_alias']);
		if ($request['url_alias'] == "")
		{
			$request['url_alias'] = "/";
		}
	}
}

$currentAccess = $access;
$siteSettings = lcSettings::getInstance();
$currentLanguage = $siteSettings->value('lang','current');

function getRequestedModule(& $requestArray)
{
	if (!isset($requestArray[0]) OR $requestArray[0] == "")
	{
		$siteSettings = lcSettings::getInstance();

		if ($siteSettings->hasValue("Modules",'defaultModule'))
		{
			$defModule = $siteSettings->value("Modules",'defaultModule');
			lcDebug::write("NOTICE", "Loading default module : ".$defModule);
			$Module = lcModule::loadModule($defModule);
			if (isset($requestArray[0]) and $requestArray[0] == "")
			{
				$requestArray = array_slice($requestArray, 1);
			}
		}
		else
		{
			lcDebug::write('ERROR',"No module to execute could be defined");
			return false;
		}
	}
	else
	{
		$Module = lcModule::loadModule($requestArray[0]);
		$requestArray = array_slice($requestArray, 1);

	}
	if ($Module instanceof lcModule)
	{
		if (!isset($requestArray[0]))
		{
			$Module->setDefaultView();

		}
		else
		{
			$view = $requestArray[0];
			$requestArray = array_slice($requestArray, 1);
			$Module->loadView($view);
		}
	}
	return $Module;
}

$Module = false;

if ($currentAccess != "setup")
{
    if ($request['url_alias'] == "/")
    {
        $nodeId = $siteSettings->value('TreeNodes', 'ContentNode');
        $contentMenu = lcContentMenu::fetchByNodeId($nodeId, $currentLanguage);
    }
    else
    {
        $contentMenu = lcContentMenu::fetchByPath($request['url_alias'], $currentLanguage);
    }

    if (is_array($contentMenu) and isset($contentMenu['node_id']))
    {
        $Module = lcModule::loadModule("content");
        $Module->loadView("view",array('View'=>'full','NodeId'=>$contentMenu['node_id']));
    }

}
else
{
    $Module = lcModule::loadModule("setup");
    $Module->loadView('step');
}

if (!is_object($Module))
{
	$Module = getRequestedModule($requestArray);
}


if ($siteSettings->hasValue("Design", "default"))
{
	$currentDesign = $siteSettings->value("Design", "default");
}

if ($currentAccess == 'setup')
{
    $userLogin = false;
}
else
{
    $userLogin = lcUser::can($Module);
}




if ($userLogin)
{
	if ($siteSettings->value("User", "login"))
	{

		lcSession::start();

		// gestion de la session

		if (!lcSession::hasValue('user_id') AND !$Module->isCurrent("user", "login"))
		{
			lcSession::setValue('last_request',$request['fullrequest']);
			$redirectUri = lcHTTPTool::buildUrl("/user/login");
			lcHTTPTool::redirect($redirectUri);
		}
	}
	else
	{
		$Module->errorNumber = 20;
	}


	// chargement du module d'authentification si pas logué.
}

if ($Module->isError())
{
	$Module->setModule("error");
	$Module->loadView('display',array('ErrorID'=>$Module->errorNumber));
}

$pageResult = $Module->result();


$debugOutput = "";
if ($siteSettings->value('Debug','Enabled'))
{
$duree = microtime()-$start;
lcDebug::write('NOTICE',"Fin de traitement : ".$duree);
$debugOutput = lcDebug::output();

}
$pageResult = str_replace("<!-- DEBUG -->",$debugOutput,$pageResult);

header('Content-Type:text/html;charset=utf-8');


echo $pageResult;

$out = ob_get_clean();
echo trim($out);

exit(0);


?>