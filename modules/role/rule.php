<?php

$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$Module = $Params['Module'];
$errorMsg = "";
$saveRule = false;

$ruleID = isset($Params['RuleId'])?$Params['RuleId']:null;

if (isset($ruleID))
{
    $currentRule = lcRule::fetchById($ruleID);

    $moduleName = $currentRule->attribute('module');
    $functionName = $currentRule->attribute('function');


    $functionList = lcModule::getModuleFunctions($moduleName);
    $functionParmas = array();
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
        }
    }

    $selectedParams = $currentRule->attribute('params');

    foreach ($selectedParams as $key=>$value)
    {
        if (!is_array($selectedParams[$key]))
        {
            $selectedParams[$key] = array($value);

        }

    }

    $tpl->setVariable("moduleName", $moduleName);
    $tpl->setVariable('functionName', $functionName);

    $tpl->setVariable('ruleId', $currentRule->attribute('id'));
    $tpl->setVariable('roleId', $currentRule->attribute('role_id'));
    $tpl->setVariable("functionParams",$functionParams);
    $tpl->setVariable('selectedParams', $selectedParams);
    $tpl->setVariable('roleAddPhase',3);

    $Result['content'] = $tpl->fetch('roles/editrule.tpl.php');

}
?>