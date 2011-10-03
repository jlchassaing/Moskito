<?php
$Module = $Params['Module'];
$fileID = isset($Params['FileID'])?$Params['FileID']:false;
$fileHash = isset($Params['FileHash'])?$Params['FileHash']:false;

if ($fileID!== false and is_string($fileHash))
{

    $currentRelease = lcInfo::getRelease();



    $upgradeList = lcUpgrader::getUpgradeListFrom($currentRelease);
    if (isset($upgradeList[$fileID]))
    {
        if (md5($upgradeList[$fileID]) == $fileHash)
        {
            $upgrader = lcUpgrader::load($upgradeList[$fileID]);
            $upgrader->run();
        }
    }
}

