<?php
$tpl = new lcTemplate();

$sections = lcSection::fetchAll();

lcSession::resetValue("CallerModule");
lcSession::resetValue("SectionToAssign");
lcSession::resetValue("SelectResult");

$tpl->setVariable("sections", $sections);
$Result['content'] = $tpl->fetch('sections/list.tpl.php');
return;