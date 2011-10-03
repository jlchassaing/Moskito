<?php


class lcCaptcha
{
    function __construct()
    {
        $settings = lcSettings::getInstance();
        if ($settings->hasValue("CaptchaSettings", "PublicKey"))
        {
            $this->publickKey = $settings->value("CaptchaSettings", "PublicKey");
        }

        if ($settings->hasValue("CaptchaSettings", "PrivateKey"))
        {
            $this->privateKey = $settings->value("CaptchaSettings", "PrivateKey");
        }
    }

    private $publickKey;
    private $privateKey;
    private $error;

    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance instanceOf lcCaptcha)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function requireLib()
    {
        require_once('lib/recaptcha/recaptchalib.php');
    }

    public function display()
    {
        $this->requireLib();

        echo recaptcha_get_html($this->publickKey);
    }

    public function requestHasCaptcha()
    {
        if (isset($_POST["recaptcha_challenge_field"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function check()
    {
        $this->requireLib();

        $resp = recaptcha_check_answer ($this->privateKey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid)
        {
            // What happens when the CAPTCHA was entered incorrectly
            $this->error = $resp->error;
            return false;
        } else {
            // Your code here to handle a successful verification
            return true;
        }

    }

}