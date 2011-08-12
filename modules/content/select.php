<?php

$http = lcHTTPTool::getInstance();
$tpl = new lcTemplate();
$Module = $Params['Module'];
$NodeId = $Params['NodeId'];

if ($http->hasPostVariable("SelectedButton"))
{
    if ($http->hasPostVariable('NodeId'))
    {
        $SelectedNodeId = $http->postVariable('NodeId');
    }
    if (lcSession::hasValue('CallerModule'))
    {
        $caller = lcSession::value('CallerModule');
        $tmp = explode('/',$caller);
        lcSession::setValue("SelectResult", $SelectedNodeId);
        $Module->redirectToModule($tmp[0],$tmp[1]);
    }
}
else
{
    $fullMenu = lcContentMenu::fetchMenuTree($NodeId,null,1,true);
    if ($fullMenu[0]['node_id'] == $fullMenu[1]['parent_node_id'])
    {
        $title = $fullMenu[0];
        $fullMenu = array_slice($fullMenu, 1);
    }
    else
    {
        $title['object_name'] = "Racine";
        $title['parent_node_id'] = false;
    }
    $tpl->setVariable("parent_title", $title);
    $tpl->setVariable("fullMenu", $fullMenu);

    $Result['content'] = $tpl->fetch("content/select.tpl.php");
}



?>