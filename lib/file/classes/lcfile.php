<?php

/*!
 *
 * This class deals with fil manipulation storing and reading
 * \author jlchassaing
 *
 */
class lcFile
{
	private $fileInfos;
	private $xmlFileInfo;

	private static $FIELDS = array('name','type','filename','tmp_name','extension','size','created');

	/*!
	 *
	 * The lcFile object can be instanciated with and xml file description
	 * which is set like this
	 * <file>
	 * 	<name>[fileName]</name>
	 *  <size>[fileSize]</size>
	 *  <type>[mimeType]</type>
	 *  <created>[Creation date]</created>
	 * </file>
	 * \param string $fileDescription
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

	/*!
	 *
	 * Chek if a file has been set for this content
	 */
	public function hasFile()
	{
	    if (is_array($this->fileInfos))
	    {
    	    $fullPath = $this->fullPath();
            if (isset($this->fileInfos) and file_exists($fullPath))
            {
                return true;
            }
            else {
                return false;
            }
	    }
		else
		{
			return false;
		}
	}

	public function setFileName($fileName)
	{
	    $infos = pathinfo($fileName);
	    $this->fileInfos['filename'] = $infos['filename'];
	    $this->fileInfos['extension'] = $infos['extension'];
	    $this->fileInfos['name'] = $fileName;
	}

	/*!
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

	public function setAttribute($name,$value)
	{
	    $this->fileInfos[$name] = $value;
	}
	/*!
	 * get the filename, from an initial file name, test
	 * if a file exists with the same name, and adds an index if so.
	 * \param string $fileName initial file name
	 * \return string
	 */
	public function searchSameName($fileName)
	{

	    $filePath = $this->path();
	    $ext = $this->fileInfos['extension'];

        return self::fixSameName($fileName, $ext, $filePath);
	}


	public static function fixSameName($fileName,$ext,$filePath)
	{
        $cleanFileName = "$fileName.$ext";
        $index = 1;
        while (file_exists($filePath."/".$cleanFileName))
        {
            $cleanFileName = $fileName."_$index";
            $cleanFileName = "$cleanFileName.$ext";
            $index++;
        }
        return $cleanFileName;
	}



	public function fileName()
	{
		return $this->fileInfos['name'];
	}

	public function fullPath()
	{
		return $this->path()."/".$this->fileName();
	}

	public function url()
	{
	    return lcHTTPTool::buildUrl($this->path()."/".$this->fileName(),true,false);
	}


	public function unsetValue($name)
	{
	    unset($this->fileInfos[$name]);
	}


	/*!
	 *
	 * \return the xmlString of file Infos
	 */
	public function getXmlFileInfo()
	{
		return $this->xmlFileInfo;
	}

	/*!
	 *
	 * Set the file information the given attribute must be
	 * an Array
	 * \param array $aFileInfo
	 */
	public function setFileInfos($aFileInfo = null)
	{
	    if ($aFileInfo == null)
	    {
	        $aFileInfo = $this->fileInfos;
	    }
		if (is_array($aFileInfo))
		{
            $this->fileInfos = $aFileInfo;
			$this->xmlFileInfo = self::buildXmlData($aFileInfo);
		}
		else
		{
			return false;
		}

	}

	public static function buildXmlData($dataArray)
	{

            $xmlData = "<?xml version='1.0'?>";
            $xmlData .= "<file>\n";

            foreach (self::$FIELDS as $fieldName)
            {
                if (isset($dataArray[$fieldName]))
                {
                    $xmlData .= "<$fieldName>".$dataArray[$fieldName]."</$fieldName>\n";
                    $dataArray[$fieldName] = $dataArray[$fieldName];
                }
            }
            $xmlData .= "</file>\n";
            return  $xmlData;

	}

	/*!
	 * return the fileInfo Array
	 * \return array
	 */
	public function fileInfo()
	{
	    return $this->fileInfos;
	}

	public function remove()
	{
	    $filePath = $this->fullPath();
	    unlink($filePath);
	}




}