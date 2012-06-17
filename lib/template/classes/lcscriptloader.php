<?php

/*!
 \class lcScriptLoader lcscriptloader.php
 \version 0.1
 \author jean-luc Chassaing

 helps managing javascript files required by the template
 take care to load them in the correct order and loades each of them once
 */
class lcScriptLoader
{
    private static $instance;
    private $scripts;

    /*!
     class constructor
     */
    private function __construct()
    {
        $this->scripts = array();
    }

    /*!
     singleton method
     */
    public static function getInstance()
    {
        if (!self::$instance instanceOf lcScriptLoader)
        {
            self::$instance = new lcScriptLoader();
        }
        return self::$instance;
    }

    /*!
     registers the $scriptName file as a required file
     and saves it.
     \param string $scriptName
     */
    public function required($scriptName)
    {
        if (!is_array($scriptName))
        {
            $scriptName = array($scriptName);
        }
        foreach ($scriptName as $value)
        {
            if (!isset($this->scripts[$value]))
            {
                $this->scripts[$value] = lcDesign::designUrl("javascript/".$value);
            }
        }

    }

    /*!
     load all the script registered in scripts array
     by loading it rights all the script tags
     */
    public static function load($scriptlist = false)
    {
        $scriptLoader = self::getInstance();

        if (is_array($scriptLoader->scripts))
        {
        
            foreach ($scriptLoader->scripts as $script)
            {

                echo "<script type=\"text/javascript\"  src=\"$script\" ></script>\n";

            }

        }
        if ($scriptlist !== false)
        {
            if (!is_array($scriptlist))
            {
                $scriptlist = array($scriptlist);
            }
            foreach ($scriptlist as $script)
            {
                $path = lcDesign::designUrl("javascript/".$script);
                if (!in_array($path, $scriptLoader->scripts))
                {
                    echo "<script type=\"text/javascript\" src=\"$path\" ></script>\n";
                }
                

            }
        }



    }



}

?>