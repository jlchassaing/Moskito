<?php

/**
 *
 * This class specificly deal with image files
 * @author jlchassaing
 *
 */
class lcImage extends lcFile
{

	public function path()
	{
		$settings = lcSettings::getInstance();
		$storageDir = $settings->value('FileSettings','StorageDir');
		$rootDir = $GLOBALS['SETTINGS']['siteRootDir'];
		$splitedDate = explode("-",$this->attribute('created'));
		return $storageDir."/image/".$splitedDate[2]."/".$splitedDate[1]."/".$splitedDate[0];
	}

	public function getHandler()
	{
		return new lcImageHandler($this);
	}

	public function remove()
	{
		
		$matchNameString = "/^".$this->attribute('basename')."(_\w+){0,1}\.".$this->attribute('extension')."$/";

		$dir= $this->path();

		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file != "." AND $file != "..")
					{
						if (preg_match($matchNameString, $file))
						{
							unlink($dir."/".$file);
						}
					}
						
				}
				closedir($dh);
			}
		}
	}
}