<?php

/*!
  \class lcMail lcmail.php
  \version 0.1
  \ingroup lcKernel
  \author Jean-Luc Chassaing

  handels mail sending

 */
class lcMail
{


    /*!
     \var string $from mail sender
     */
    private $from;

    /*!
     \var string $to
     */
    private $to;

    /*!
     \var string $subject mail subject
     */
    private $subject;

    private $text;

    private $type;

    private $charset;

    private $error;

    private $userAgent;

    const DEFAULT_MAIL_TYPE = "text/plain";
    const DEFAULT_MAIL_CHARSET = "utf-8";
    const DEFAULT_USER_AGENT = "Moskito, Version 1.0";

    /*!
     class constructor
     */
    public function __construct()
    {
        $settings = lcSettings::getInstance();
        $mailTypes = array();
        if ($settings->hasGroup("MailSettings"))
        {
            if ($settings->hasValue("MailSettings","Type"))
            {
                $mailTypes = $settings->value("MailSettings","Type");
                if ($settings->hasValue("MailSettings","DefaultMailType"))
                {
                    $defaultType = $settings->value("MailSettings","DefaultMailType");
                    $this->type = isset($mailTypes[$defaultType])?$mailTypes[$defaultType]:self::DEFAULT_MAIL_TYPE;
                }
                if ($settings->hasValue("MailSettings","Encoding"))
                {
                    $encoding = $settings->value("MailSettings","Encoding");
                    $this->charset = $encoding;
                }
                else
                {
                    $this->charset = self::DEFAULT_MAIL_CHARSET;
                }
                if ($settings->hasValue("MailSettings","UserArgent"))
                {
                   $agent = $settings->value("MailSettings","UserArgent");
                    $this->userAgent = $agent;
                }
                else
                {
                    $this->userAgent = self::DEFAULT_USER_AGENT;
                }
            }


        }
    }

    /*!
     mail sender
     */
    public function send()
    {
       $mailoption = "-f".$this->from['mail'];
       if (!mail($this->to,$this->subject,$this->text,$this->headers(),$mailoption))
       {
           $this->error = "Mail could not be sent";
       }
    }

    /*!
     set mail headers
     builds the mail headers
     \return string

     Date: Tue, 13 Sep 2011 13:52:38 +0200
        From:
        Reply-To:
        MIME-Version: 1.0
        Content-Type: text/html; charset=utf-8
        Content-Transfer-Encoding: 8bit
        Content-Disposition: inline
        User-Agent: Moskito, Version 0.1
     */
    private function headers()
    {
        $headers = "";
        $headers .= "Date :".date( 'r' )."\n";
        $headers .= 'MIME-Version: 1.0' . "\n";

        $headers .= "Content-type: ".$this->type."; charset=". $this->charset ."\n";
        $headers .= "Content-Transfer-Encoding: quoted-printable\n".
                    "Content-Disposition: inline\n".
                    "User-Agent: ".$this->userAgent."\n";

        if (isset($this->from))
        {
            $headers .= "From: ".$this->from['name']." <". $this->from['mail'] .">\n" .
                        "Reply-To: ".$this->from['name']." <". $this->from['mail'] .">\n";
        }

        return $headers;
    }

    public function setFrom($email,$name=null)
    {
        $this->from = array();
        $this->from['name'] = ($name == null)?$email:$name;
        $this->from['mail'] = $email;
    }

    public function __set($name,$value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

}


?>