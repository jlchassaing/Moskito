<?php

$Module = $Params['Module'];

$http = lcHTTPTool::getInstance();

$errors = array();
 $tpl = new lcTemplate();

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

        $captchaMgr = lcCaptcha::getInstance();
        $validCaptcha = true;
        if ($captchaMgr->requestHasCaptcha())
        {
            $validCaptcha = $captchaMgr->check();
        }

        $recipient = $contentObject->attribute('recipient');

        if (!lcStringTools::isEmail($recipient))
        {
            $errors['recipient']  = "Recipient email error";
        }

        $sender = $contentObject->attribute('email');

        if (!lcStringTools::isEmail($sender))
        {
            $errors['email']  = "Sender email error";
        }

        if (count($errors) == 0)
        {

            $tpl->setVariable('Object', $contentObject);
            $mailContent = $tpl->fetch('content/mailcontent.tpl.php');

            $mail = new lcMail();

            $mail->from = $sender;
            $mail->to = $recipient;
            $mail->subject = $contentObject->attribute('subject');
            $mail->text = $mailContent;
            $mail->send();

            $Result['content'] = $tpl->fetch('content/mailconfirm.tpl.php');
        }
        else
        {

            if ($contentObject instanceof lcContentObject)
            {
                $classIdentifier = $contentObject->attribute('class_identifier');
                $templateRule = lcTemplateRule::getInstance();
                $rulesSet = array('Class'    => $classIdentifier,
		                  'Action'   => 'content/view.tpl.php');
                $tplPath = $templateRule->getTemplate($rulesSet);

                if ($tplPath == "")
                    $tplPath = "content/view.tpl.php";
                $tpl->setVariable("errors", $errors);
                $tpl->setVariable("object", $contentObject);
                if (isset($tplPath))
                {
                    $Result['content'] = $tpl->fetch($tplPath);
                }
            }
        }
    }
}