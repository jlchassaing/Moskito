<?php
include_once 'kernel/setup/lcsetup.php';

$tpl = new lcTemplate();
$http = lcHTTPTool::getInstance();
$InstallationStep = null;

if ($http->hasPostVariable("NextStepButton"))
{
    if ($http->hasPostVariable("CurrentStepValue"))
    {
        $InstallationStep = $http->postVariable("CurrentStepValue");
    }
}

$setup = new lcSetup($InstallationStep);

$installMessages = $setup->runStep();
while ($installMessages !== false AND $installMessages['skip'])
{
    if (!$setup->isLast())
    {
        $InstallationStep = $setup->next();
        $installMessages = $setup->runStep();
    }
    else
    {
        break;
    }
}

$installSteps = $setup->steps();

if (isset($installMessages['datas']))
{
    if (is_array($installMessages['datas']))
    {
        foreach ($installMessages['datas'] as $key => $value)
        {
            $tpl->setVariable($key, $value);
        }
    }
}

$tpl->setVariable("title", $installMessages['title']);
$tpl->setVariable("message", $installMessages['message']);
$tpl->setVariable("current_step", $InstallationStep);
$tpl->setVariable("stepslist", $installSteps);

$stepId = $setup->getStepId();

$Result['content'] = $tpl->fetch("setup/step_$stepId.tpl.php");



?>