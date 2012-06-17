<?php
$Module = $Params['Module'];
$fileID = isset($Params['FileID'])?$Params['FileID']:false;
$fileHash = isset($Params['FileHash'])?$Params['FileHash']:false;

$tpl = new lcTemplate();

$currentRelease = lcInfo::getRelease();
$upgradeList = lcUpgrader::getUpgradeListFrom($currentRelease);

if ($fileID!== false and is_string($fileHash))
{

    if (isset($upgradeList[$fileID]))
    {
        if (md5($upgradeList[$fileID]) == $fileHash)
        {
            $upgrader = lcUpgrader::load($upgradeList[$fileID]);

                $upgrader->run();


        }
    }
}

$tpl->setVariable('release',$currentRelease);
$tpl->setVariable('scripts', $upgradeList);

$Result['content'] = $tpl->fetch('setup/upgrade.tpl.php');


