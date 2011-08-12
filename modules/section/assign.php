<?php

$SectionID = (isset($Params['SectionID']))?$Params['SectionID']:false;

$http = lcHTTPTool::getInstance();
$tpl = new lcTemplate();
$Module = $Params['Module'];
if ($Module->isCurrentAction('set'))
{
    if (lcSession::hasValue("SelectResult"))
    {
        $SelectedNodeID = lcSession::value("SelectResult");
        $SectionToAssign = lcSession::value("SectionToAssign");
        if (is_array($SelectedNodeID))
        {
            $section = lcSection::fetchByName($SectionToAssign);
            if($section instanceOf lcSection)
            {
                foreach($SelectedNodeID as $nodeId)
                {
                    lcMenu::setSection($nodeId, $section->attribute('id'));
                }
            }


        }
    }
    $Module->redirectToModule('section','list');
}
if ($Module->isCurrentAction('assign'))
{

    lcSession::setValue('CallerModule', 'section/set');
    lcSession::setValue('SectionToAssign',$SectionID);

    $Module->redirectToModule('content','select',array('NodeId' => 1));

}


?>