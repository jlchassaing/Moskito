 <?php
$Module = $Params['Module'];

$currentRelease = lcInfo::getRelease();



$upgradeList = lcUpgrader::getUpgradeListFrom($currentRelease);

$tpl = new lcTemplate();

$tpl->setVariable('release',$currentRelease);
$tpl->setVariable('scripts', $upgradeList);

$Result['content'] = $tpl->fetch('setup/upgrade.tpl.php');

?>