<?php

/*!

 \class lcStringTools lcstringtools.php
 \version 0.1

 provides string manipulation static methods.


 \author jlchassaing

 */
class lcStringTools
{
	/*!

	 builds a normalize name without spaces, special caracters and accents
	 \param string $string
	 \return string
	 */
	static function makeNormName($string)
	{
        //$name = mb_check_encoding($string,"UTF-8");
		$name = trim($string);
		$converArray= array(
							array("&"," ","'","à","á","â","ã","ä","ç","è","é","ê","ë","ì",
								  "í","î","ï","ñ","ò","ó","ô","õ","ö","ù","ú","û","ü","ý",
								  "ÿ","À","Á","Â","Ã","Ä","Ç","È","É","Ê","Ë","Ì","Í","Î",
								  "Ï","Ñ","Ò","Ó","Ô","Õ","Ö","Ù","Ú","Û","Ü","Ý","?","!",".","\""),
					  		array("-","-","-","a","a","a","a","a","c","e","e","e","e","i",
					  			  "i","i","i","n","o","o","o","o","o","u","u","u","u","y",
					  			  "y","A","A","A","A","A","C","E","E","E","E","I","I","I",
					  			  "I","N","O","O","O","O","O","U","U","U","U","Y","","","",""));
		$tmpStr = str_replace($converArray[0],$converArray[1],$name);
		$tmpStr = str_replace("--", "-", $tmpStr);
		$tmpStr = str_replace("--", "-", $tmpStr);
		$tmpStr = strtolower($tmpStr);
		return $tmpStr;
	}

	/*!
	  Check if the provided string is an email.
	 */
	static function isEmail($string)
	{
	    if (!preg_match("#^([a-z0-9._-]+)@([a-z0-9.-]{2,}([.][a-z]{2,})*[.][a-z]{2,3})$#", $string))
	    {
	        return false;
	    }
	    else
	    {
	        return true;
	    }
	}

}