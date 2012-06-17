<?php


$http = lcHTTPTool::getInstance();
$tpl = new lcTemplate();
$settings = lcSettings::getInstance();
$Module = $Params['Module'];
$ParentNodeId = 1;
if ($http->hasPostVariable("ParentNodeIDValue"))
{
    $ParentNodeId = $http->postVariable("ParentNodeIDValue");
}

if ($http->hasPostVariable("EditButton"))
{
	// editing content
	if ($http->hasPostVariable("ObjectIdValue"))
	{
		$objectID = $http->postVariable("ObjectIdValue");

		if ($http->hasPostVariable("ContentLanguageValue"))
		{
			$lang = $http->postVariable("ContentLanguageValue");
		}
		else
		{
			$lang = $settings->value('lang', 'current');
		}

		$Module->redirectToModule('content','edit',array('ObjectId' => $objectID,'Lang'=>$lang));

	}

}
elseif($http->hasPostVariable("CreateContentButton"))
{
	$classId = "";
	if ($http->hasPostVariable("ContentClassIdentifier"))
	{
		$classId = $http->postVariable("ContentClassIdentifier");
	}
	$langId = "";
	if ($http->hasPostVariable("contentLanguageValue"))
	{
		$langId = $http->postVariable("contentLanguageValue");
	}

	$params = array('ClassId'=>$classId,'Lang'=>$langId);
    if ($http->hasPostVariable("ParentNodeIDValue"))
    {

        lcSession::setValue("ParentNodeID", $http->postVariable("ParentNodeIDValue"));

    }


	$Module->redirectToModule('content','create',$params);
}
elseif($http->hasPostVariable("RemoveButton"))
{
	if ($http->hasPostVariable("ObjectIdValue"))
	{
		$objectID = $http->postVariable("ObjectIdValue");

		$Module->redirectToModule('content','delete',array('ObjectId' => $objectID));

	}
	else
	{
	    $Module->redirectToModule('content','view',array('full',1));
	}



}
elseif($http->hasPostVariable("DeleteSelectedContent"))
{
    if ($http->hasPostVariable("contentIds"))
    {
        $objectID = $http->postVariable("contentIds");

        $menuList = lcContentMenu::fetchMenuByObjectId($objectID,null,true,true);
        foreach ($menuList as $menu)
        {
            $object = lcContentObject::fetchByNodeId($menu['node_id'],$menu['lang'],true);
            $object->removeObject();
            if (!lcMenu::hasChildren($menu['node_id']))
            {
                lcContentMenu::removeMenu($menu['node_id']);
            }
        }

    }
    $Module->redirectToModule('content','view',array('full',$ParentNodeId));
}

elseif($http->hasPostVariable("UpdateMenuSorting"))
{
    $aMenuSortValues = $http->postVariable("MenuSortValue");
    $aMenuNodeValues = $http->postVariable("NodeIdVAlue");

    $db = lcDB::getInstance();
    if (count($aMenuNodeValues) == count($aMenuSortValues))
    {
        $cte = count($aMenuNodeValues);
        for($i =0; $i < $cte ; $i++)
        {
            $query = "UPDATE menu SET sort_val = ".$aMenuSortValues[$i]. " WHERE node_id = ". $aMenuNodeValues[$i];
            $db->query($query);
        }
    }

    if ($http->hasPostVariable("PageURI"))
    {
        $uri = $http->postVariable("PageURI");

        $http->redirect(lcHTTPTool::buildUrl($uri));
        return;
    }

}
elseif ($http->hasPostVariable("SelectedBrowseContent"))
{
    if ($http->hasPostVariable("selectedNode"))
    {
        $selectedNode = $http->postVariable("selectedNode");
        $parameters = lcSession::value("BrowseParameters");
        
        $NodeToMove = $parameters['saved_data']['node_id'];
        
        $contentMenu = lcContentMenu::fetchByNodeId($NodeToMove);
        $contentMenu->moveToNode($selectedNode);
        
        $Module->redirectToModule('content','view',array('full',$selectedNode));
    }    
    
    
}

?>