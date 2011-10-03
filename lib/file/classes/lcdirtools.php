<?php


class lcDirTools
{


    public static function dirList($dir,$filePattern = false)
    {
        // Open a known directory, and proceed to read its contents
        $result = array();
        if (substr($dir, -1) != "/")
        {
            $dir .= "/";
        }
        if (is_dir($dir))
        {
            if ($dh = opendir($dir))
            {
                while (($file = readdir($dh)) !== false)
                {
                    if ($file != "." and $file != "..")
                    {
                        if ($filePattern !== false)
                        {
                            if (preg_match($filePattern, $file))
                             $result[] = $dir.$file;
                        }
                        else
                        {
                             $result[] = $dir.$file;
                        }

                    }
                }
                closedir($dh);
            }
        }
        return $result;
    }

}