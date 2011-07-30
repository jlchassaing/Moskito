<?php

/*!
 \class lcStyleLoader lcstyleloader.php
 \version 0.1
 \author jean-luc Chassaing

 helps managing css files required by the template
 take care to load them in the correct order and loades each of them once
 */
class lcStyleLoader
{
    private static $instance;
    private $cssfiles;

    /*!
     class constructor
     */
    private function __construct()
    {
        $this->cssfiles = array();
    }

    /*!
     singleton method
     */
    public static function getInstance()
    {
        if (!self::$instance instanceOf lcStyleLoader)
        {
            self::$instance = new lcStyleLoader();
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
            if (!isset($this->cssfiles[$value]))
            {
                $this->cssfiles[$value] = lcDesign::designUrl("stylesheets/".$value);
            }
        }

    }

    /*!
     gets the loaded css files array
     */
    public function loadedArray()
    {
        return $this->cssfiles;
    }

    /*!
     load all the css files registered in $cssfiles array
     by loading it rights all the style tags
     */
    public static function load($styles = false)
    {
        $styleLoader = self::getInstance();
        echo " <style type=\"text/css\">\n";
        if (is_array($styleLoader->cssfiles))
        {
             
            foreach ($styleLoader->cssfiles as $script)
            {
                 
                echo "@import url($script); \n";

            }

        }
        if ($styles !== false)
        {
            if (!is_array($styles))
            {
                $styles = array($styles);
            }
            foreach ($styles as $script)
            {
                $path = lcDesign::designUrl("stylesheets/".$script);
                echo "@import url($path) \n";

            }
        }


        echo "</style>\n";


    }



}

?>