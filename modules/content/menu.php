<?php
$tpl = new lcTemplate();
$Module = $Params['Module'];
$http = lcHTTPTool::getInstance();
$view = (isset($Params['View']))?$Params['View']:false;
$MenuId = (isset($Params['MenuId']))?$Params['MenuId']:false;
$templateFile = "";
$errorMessage = "";

if ($http->hasPostVariable("AddMenuButton"))
{


}
elseif ($http->hasPostVariable("EditButton"))
{
	if ($http->hasPostVariable("MenuIdValue"))
	{
		$id = $http->postVariable("MenuIdValue");
		$editableMenu = lcContentMenu::fetchById($id);
		if ($editableMenu)
		{

			$tpl->setVariable("menu", $editableMenu);
			$templateFile = "menu/edit.tpl.php";
		}
		else
		{
			$errorMessage = "No menu was found with the id : $id";
			lcDebug::write("ERROR", "No menu was found with the id : $id");
		}
	}
	else
	{
		lcDebug::write("ERROR", "missing menuIdValue post value");
	}
}
elseif ($http->hasPostVariable("SaveMenuButton"))
{
	if ($http->hasPostVariable("MenuIdValue"))
	{
		$id = $http->postVariable("MenuIdValue");
		$editableMenu = lcContentMenu::fetchById($id);

		if ($http->hasPostVariable("MenuMameValue"))
		{
			$newLibelle = $http->postVariable("MenuMameValue");
			if ($newLibelle != "")
			{

				if ($editableMenu instanceof lcContentMenu)
				{

					$oldName = $editableMenu->attribute("name");
					$oldPathName = $editableMenu->makeNormName($oldName);
					$newPathName = $editableMenu->makeNormName($newLibelle);

					$editableMenu->setAttribute("name", $newLibelle);

					$editableMenu->store();
					if ($oldPathName != $newPathName)
					{
						$editableMenu->updateChildrenNames();
					}

					$uri = $http->makeUrl('/content/menu/manage');
					lcHTTPTool::redirect($uri);

				}
			}
			else
			{
				$errorMessage ="The field Name can't be empty.";
			}
		}
		else
		{
			lcDebug::write("ERROR", "Missing field to save the form");
			$errorMessage= "An internal error has occured, please contact the webmaster";
		}
	}
	else
	{
		lcDebug::write("ERROR", "missing menuIdValue post value");
		$errorMessage= "An internal error has occured, please contact the webmaster";
	}
	if ($errorMessage != "")
	{
			$tpl->setVariable("menu", $editableMenu);
			$tpl->setVariable("error", $errorMessage);
			$templateFile = "menu/edit.tpl.php";
	}
}
elseif($http->hasPostVariable('SaveMenuUpdates'))
{
    $aSortVals = $http->postVariable('MenuSortValue');
    $aMenuIdValues = $http->postVariable('MenuIdValue');
    $aMenuOrders = $http->postVariable('MenuIdOrder');
    $aMenuNewSortValues = $http->postVariable('MenuNewSortValue');

    foreach($aMenuNewSortValues as $key=>$newSortValue)
    {
        if ($newSortValue != $aSortVals[$key])
        {
            // save new sort value
            $menu = lcMenu::fetchById($aMenuIdValues[$key]);
            $sort_val = $menu->attribute('sort_val');
            $newSortVal = substr($sort_val,0,-2);
            if (strlen($newSortValue)  == 1)
            {
                $newSortValue = "0$newSortValue";
            }
            $newSortVal = $newSortVal.$newSortValue;
            $menu->setAttribute('sort_val',$newSortVal);
            $menu->store();
        }
    }
    $Params['view'] = "manage";
    $Module->redirectToModule('content','menu',$Params);


}
else
{
	if ($view == "manage")
	{
		$menuList = lcContentMenu::fetchMenuTree(1,null,null,true);
		$tpl->setVariable("menu", $menuList);
		$templateFile = "menu/adminlist.tpl.php";
	}
	if ($view == "edit")
	{
	    if ($MenuId)
	    {
    	    $editableMenu = lcContentMenu::fetchById($MenuId);
    	    if ($editableMenu)
    	    {

    	        $tpl->setVariable("menu", $editableMenu);

    	    }
	    }
	    else
	   {
	       $tpl->setVariable("error","Aucun menu trouvé pour l'édition");
	    }

	    $templateFile = "menu/edit.tpl.php";

	}

}


$Result['content'] = $tpl->fetch($templateFile);
?>