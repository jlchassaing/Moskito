<?php

/*!
 \class lcsession lcsession.php
 \author jlchassaing
 \version 0.1

 handels user sessions
 */
class lcSession
{



    private static $hasStarted;
    private static $instance;
    private $sessionHasStarted;
    
    public static function instance()
    {
        if (! self::$instance instanceOf lcSession)
        {
            self::$instance = new lcSession();
            
        }
    }


    /*!
     class constructor
     */
    public function __construct()
    {
        $this->sessionHasStarted = self::$hasStarted;
    }
    
    public function hasVariable($name)
    {
        return self::hasValue($name);
    }
    
    public function variable($name)
    {
        return self::value($name);
    }
    
    public function setVariable($name,$value)
    {
        self::setValue($name, $value);
    }

    public static function start()
    {
        if (!isset(self::$hasStarted) or self::$hasStarted === false)
        {
            $siteSettings = lcSettings::getInstance();
            $sessionLifeTime = $siteSettings->value('User','sessionlifetime');
            session_set_cookie_params($sessionLifeTime);
            session_start();
            self::$hasStarted = true;
        }
    }

    public static function stop()
    {
        if (self::$hasStarted)
        {
            $_SESSION = array();
            session_destroy();
            self::$hasStarted = false;
        }
    }

    /*!
     check if a variable is set in the session
     \param string $name variable name
     \return boolean
     */
    public static function hasValue($name)
    {
        return isset($_SESSION[$name]);
    }

    /*!
     set a session value
     \param string $name variable name
     \param mixed $value variable value

     */
    public static function setValue($name,$value)
    {
        $_SESSION[$name] = $value;
    }

    /*!
     return a session variable value
     \param string $name variable name
     \return mixed
     */
    public static function value($name)
    {
        if (isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
        else
        {
            return null;
        }
    }

    /*!
     reset session value
     \param string $name session value to reset
     \return boolean
     */
    public static function resetValue($name)
    {
        if (isset($_SESSION[$name]))
        {
            unset($_SESSION[$name]);
            return true;
        }
        else
        {
            return false;
        }
    }
}