<?php
$http = lcHTTPTool::getInstance();
$tpl = new lcTemplate();
$Module = $Params['Module'];

if ($http->hasPostVariable("SaveNewSectionButton"))
{
    if ($http->hasPostVariable("NewSectionValue"))
    {
        $newSection = $http->postVariable("NewSectionValue");
        $newSection = lcStringTools::makeNormName($newSection);
        $section = lcSection::fetchByName($newSection);
        if (!$section instanceOf lcSection)
        {
            lcSection::add($newSection);
        }

        $Module->redirectToModule('section','list');


    }

}
else
{
    $Result['content'] = $tpl->fetch("sections/add.tpl.php");
}