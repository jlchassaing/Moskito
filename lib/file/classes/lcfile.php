<?php

/**
 *
 * This class deals with fil manipulation storing and reading
 * @author jlchassaing
 *
 */
class lcFile
{
	private $fileInfos;
	private $xmlFileInfo;

	private static $FIELDS = array('name','type','filename','basename','extension','size','created');

	/**
	 *
	 * The lcFile object can be instanciated with and xml file description
	 * which is set like this
	 * <file>
	 * 	<name>[fileName]</name>
	 *  <size>[fileSize]</size>
	 *  <type>[mimeType]</type>
	 *  <created>[Creation date]</created>
	 * </file>
	 * @param string $fileDescription
	 */
	public function __construct($fileDescription)
	{
		if (is_string($fileDescription) and $fileDescription != "")
		{
			$xml = simplexml_load_string($fileDescription);
			foreach ($xml->children() as $key=>$value)
			{
				$this->fileInfos[$key] = (string) $value;
			}
		}

	}

	/**
	 *
	 * Chek if a file has been set for this content
	 */
	public function hasFile()
	{
		if (isset($this->fileInfos) and is_array($this->fileInfos))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 *
	 * Return the full image path
	 * The path is built
	 */
	public function path()
	{
		$settings = lcSettings::getInstance();
		$storageDir = $settings->value('FileSettings','StorageDir');
		$splitedDate = explode("-",$this->fileInfos['created']);
		return $storageDir."/file/".$splitedDate[2]."/".$splitedDate[1]."/".$splitedDate[0];
	}

	public function getHandler()
	{
	    return new lcFileHandler($this);
	}

	public function attribute($name)
	{
		if (isset($this->fileInfos[$name]))
		{
			return $this->fileInfos[$name];
		}
	}


	public function fileName()
	{
		return $this->fileInfos['filename'];
	}

	public function fullPath()
	{
		return $this->path()."/".$this->fileName();
	}

	public function url()
	{
	    return lcHTTPTool::buildUrl($this->path()."/".$this->fileName(),true,false);
	}



	/**
	 *
	 * return the xmlString of file Infos
	 */
	public function getXmlFileInfo()
	{
		return $this->xmlFileInfo;
	}

	/**
	 *
	 * Set the file information the given attribute must be
	 * an Array
	 * @param array $aFileInfo
	 */
	public function setFileInfos($aFileInfo)
	{
		if (is_array($aFileInfo))
		{
			$xmlData = "<?xml version='1.0'?>";
			$xmlData .= "<file>\n";

			foreach (self::$FIELDS as $fieldName)
			{
				if (isset($aFileInfo[$fieldName]))
				{
					$xmlData .= "<$fieldName>".$aFileInfo[$fieldName]."</$fieldName>\n";
					$this->fileInfos[$fieldName] = $aFileInfo[$fieldName];
				}
			}
			$xmlData .= "</file>\n";
			$this->xmlFileInfo = $xmlData;
		}
		else
		{
			return false;
		}

	}




}