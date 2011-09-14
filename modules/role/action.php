<?php

$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$Module = $Params['Module'];
$errorMsg = "";
$saveRule = false;
if ($http->hasPostVariable("addRuleButton"))
{
    if ($http->hasPostVariable("RoleIdValue"))
    {
        $roleId = $http->postVariable("RoleIdValue");


    }
    else
    {
        lcDebug::write("Error", "No role Id was passed");
        $errorMsg = "No Role Id was specified";
    }
    if ($errorMsg == "")
    {
        $moduleList = lcModule::getModuleList();
        $tpl->setVariable("roleId", $roleId);
        $tpl->setVariable("roleAddPhase", 1);
        $tpl->setVariable("moduleList",$moduleList);
        $Result['content'] = $tpl->fetch("roles/addrule.tpl.php");
    }
}
elseif($http->hasPostVariable("SaveRuleButton"))
{
    $params = "";
    if ($http->hasPostVariable("RoleIdValue"))
    {
        $roleId = $http->postVariable("RoleIdValue");
    }
    if ($http->hasPostVariable("ProcessPhase"))
    {
        $process = $http->postVariable("ProcessPhase");
        if ($process == 1)
        {
            $moduleName = $http->postVariable("moduleNameValue");
            if ($moduleName != "")
            {
                if ($moduleName == "all")
                {
                    $saveRule = true;
                }
                else
                {
                    $functionList = lcModule::getModuleFunctions($moduleName);
                    $tpl->setVariable("roleId", $roleId);
                    $tpl->setVariable("roleAddPhase", 2);
                    $tpl->setVariable("moduleName",$moduleName);
                    $tpl->setVariable("functionList",array_keys($functionList));
                    $Result['content'] = $tpl->fetch("roles/addrule.tpl.php");
                }

            }
        }
        elseif ($process == 2)
        {
            $moduleName = $http->postVariable("ModuleNameValue");
            $functionName = $http->postVariable("functionNameValue");
            if ($functionName == "all")
            {
                $saveRule = true;
            }
            else
            {
                 $functionList = lcModule::getModuleFunctions($moduleName);
                 if (count($functionList[$functionName]) != 0)
                 {
                     /// manage rule params
                     if (is_array($functionList[$functionName]))
                     {
                         $functionParams = array();
                         foreach ($functionList[$functionName] as $key=>$filter)
                         {
                             $functionParams[$key]['list'] = $filter[0]::$filter[1]();
                             $functionParams[$key]['keys'] = array($filter[2],$filter[3]);
                         }
                         $tpl->setVariable("roleId", $roleId);
                         $tpl->setVariable("roleAddPhase", 3);
                         $tpl->setVariable("moduleName",$moduleName);
                         $tpl->setVariable("functionName",$functionName);
                         $tpl->setVariable("functionParams",$functionParams);
                         $Result['content'] = $tpl->fetch("roles/addrule.tpl.php");
                     }
                 }
                 else
                 {
                     $saveRule = true;
                 }
            }
        }
        elseif ($process == 3)
        {
            $moduleName = $http->postVariable("ModuleNameValue");
            $functionName = $http->postVariable("FunctionNameValue");
            if ($http->hasPostVariable("paramFieldList"))
            {
                $paramFields = $http->postVariable("paramFieldList");
                if (is_array($paramFields))
                {
                    $parmas = array();
                    foreach($paramFields as $field)
                    {
                        $type = substr($field, 6);
                        $paramFieldValue = $http->postVariable($field);
                        $params[$type] = is_array($paramFieldValue)?implode(';',$paramFieldValue):$paramFieldValue;
                    }
                }
            }
            $saveRule = true;
        }

    }

    if ($saveRule)
    {
        $ruleParams['role_id'] = $roleId;
        $ruleParams['module'] = $moduleName;
        $ruleParams['function'] = $functionName;
        $ruleParams['params'] = $params;

        $newRule = new lcRule($ruleParams);
        $newRule->store();

        $Module->redirectToModule('role','list');
    }



}
elseif($http->hasPostVariable("deleteSelectedRuleButton"))
{
    $roleId = false;
    if ($http->hasPostvariable("RoleIdValue"))
    {
        $roleId = $http->postvariable("RoleIdValue");
    }
    else
    {
        $errorMsg[] = "No role defined!";

    }
    $ruleList = false;
    if ($http->hasPostvariable("ruleId"))
    {
        $ruleList = $http->postvariable("ruleId");
    }
    if ($ruleList and $roleId)
    {
        lcRule::remove(lcRule::definition(),array('id' => $ruleList,'role_id'=>$roleId));
    }


}
elseif ($http->hasPostVariable("AddNewRole"))
{
    $Module->redirectToModule('role','edit');
}

else
{
    $roleList = lcRole::fetch(lcRole::definition(),null,null,null,null,true,true);

    $tpl->setVariable("roles", $roleList);

    $Result['content'] = $tpl->fetch("roles/list.tpl.php");

}




?>