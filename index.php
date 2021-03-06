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
//$access = $accessManager->getAccessFromRequestArray($requestArray);
$access = $accessManager->getAccessFromRequestArray($request);


/*if (!$access)
{
	$access = lcSettings::getValue("AccessSettings", "Default", "settings/settings.ini");
}
else
{*/
	if ($accessManager->accessType == lcAccess::URI)
	{
		$requestArray = array_slice($requestArray, 1);
		$request['request'] = $requestArray;
		$request['url_alias'] = str_replace("/$access", "", $request['url_alias']);
		if ($request['url_alias'] == "")
		{
			$request['url_alias'] = "/";
		}
	}
//}

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

	}
	if ($Module instanceof lcModule)
	{
	    $requestArray =  is_array($requestArray)?array_slice($requestArray, 1):null;
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
	else
	{
	    $Module = lcModule::loadModule("error");
	    $Module->errorNumber = 30;
	}
	return $Module;

}

$Module = false;

if ($currentAccess != "install")
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

    if (($contentMenu instanceOf lcContentMenu))
    {
        $Module = lcModule::loadModule("content");
        $Module->loadView("view",array('View'=>'full','NodeId'=>$contentMenu->attribute('node_id')));
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
    if ($siteSettings->value("User", "login"))
    {
        $userLogin = true;
    }
    else
    {
        $userLogin = false;
    }

}


lcSession::start();

if ($userLogin)
{
    // gestion de la session

	if (!lcSession::hasValue('user_id') AND !$Module->isCurrent("user", "login"))
	{
		lcSession::setValue('last_request',$request['fullrequest']);
		$redirectUri = lcHTTPTool::buildUrl("/user/login");
		lcHTTPTool::redirect($redirectUri);
	}

	// chargement du module d'authentification si pas logué.
}

$user = lcUser::getCurrentUser();
if (!$user->can($Module))
{
    $Module->errorNumber = 20;
}

if ($Module->isError())
{
	$Module->setModule("error");
	$Params = array('ErrorID'=>$Module->errorNumber,
	                'RedirectUrl' => $request['fullrequest']);
	$Module->loadView('display',$Params);
}

$ModuleResult = $Module->result();

$tpl = new lcTemplate();

$tpl->setVariable('MainResult', $ModuleResult['content']);

if (!is_array($contentMenu) and $contentMenu instanceOf lcContentMenu)
$tpl->setVariable("currentNodeId", $contentMenu->attribute('node_id'));
if (isset($ModuleResult['path']))
{
    $tpl->setVariable("path" , $ModuleResult['path']);
}
$DefaultLayoutTemplate = "layout.tpl.php";
$layoutTemplate = $ModuleResult['layout'];
if ($layoutTemplate == lcModule::DEFAULT_LAYOUT)
{
    $tplRule = lcTemplateRule::getInstance();
    $layoutTemplate = false;
    if ($contentMenu instanceOf lcContentMenu)
    {
        $LayoutRule = array('Match' => array('Section'  => $contentMenu->section(),
					  	  				 'NodeId'   => $contentMenu->attribute('node_id')),
		                'Action'   => 'layout.tpl.php');
        $layoutTemplate = $tplRule->getTemplate($LayoutRule);
    }

    if (!$layoutTemplate)
    {
        $layoutTemplate = $DefaultLayoutTemplate;
    }

}
$pageResult = $tpl->fetch($layoutTemplate);


$debugOutput = "";
if ($siteSettings->value('Debug','Enabled'))
{
$duree = microtime()-$start;
lcDebug::write('NOTICE',"Fin de traitement : ".$duree);
$debugOutput = lcDebug::output();

}
$pageResult = str_replace("<!-- DEBUG -->",$debugOutput,$pageResult);

header('Content-type:text/html;charset=UTF-8');

echo $pageResult;

//include_once 'upgrade.php';

$out = ob_get_clean();
echo trim($out);

exit(0);


?>