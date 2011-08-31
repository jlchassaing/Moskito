<?php

$Module = $Params['Module'];

$http = lcHTTPTool::getInstance();

if ($http->hasPostVariable("SendFormButton"))
{
    if ($http->hasPostVariable("ObjectIdValue"))
    {
        $ObjectId = $http->postVariable("ObjectIdValue");

        $contentObject = lcContentObject::fetchById($ObjectId,true);

        foreach ($contentObject->dataMap() as $attribute)
        {
            $dataType = $attribute->datatype();
            if ($dataType->canGetFromHttp($http, $attribute))
            {
                $dataType->getFromHttp($http, $attribute);
            }
        }

        $dataMap = $contentObject->dataMap();

        if (isset($dataMap['recipient']))
        {
            $recipient = $dataMap['recipient']->content();
        }

        if (isset($dataMap['email']))
        {
            $sender = $dataMap['email']->content();
        }

        $tpl = new lcTemplate();
        $tpl->setVariable('Object', $contentObject);
        $mailContent = $tpl->fetch('content/mailcontent.tpl.php');




    }
}