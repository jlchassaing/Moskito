<?php

/*!
 \class lcDesign lcdesign.php
 \version 0.1
 \author jean-luc Chassaing

 handel designs and give tool methods
 */
class lcDesign
{
    /*!
      return the real $file path according to the loaded desing
      \param string $file
      \return string
     */
    public static function designUrl($file)
    {
        $result  = "";
        $settings = lcSettings::getInstance();
        $design = $settings->value("Design", "default");

        $content = (substr($file, 0,1) =="/")?substr($file, 1):$file;
        $testPath = "design/".$design."/".$content;
        if (file_exists($testPath))
        {
            $result = "/".$testPath;
        }
        else
        {
            $testPath = "design/standard/".$content;
            if (file_exists($testPath))
            {
                $result =  "/".$testPath;
            }

        }
        return lcHTTPTool::fileUrl($result);
    }
}

?>