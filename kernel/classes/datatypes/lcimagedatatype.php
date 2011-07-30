<?php


class lcImageDataType implements lcDatatypeInterface
{

	private $value;
	
	const DATATYPE = "image";

	function __construct(& $contentAttribute)
	{
		
		$this->value = new lcImage($contentAttribute->attribute('ltxt_value'));	
		
		
		
	}

	public function canGetFromHttp($http, & $contentAttribute)
	{
		$httpFile = lcHttpFile::getInstance();
		$fieldName = "field_".$contentAttribute->attribute('identifier')."_".self::DATATYPE;
		if ($httpFile->isUpload($fieldName))
		{
			if ($httpFile->canUplodatFile($fieldName))
			{
				$fileInfo = $httpFile->uploadedFileInfos($fieldName);
				$type = explode("/", $fileInfo['type']);
				if ($type[0] == "image")
				{
					return true;
				}
				else
				{
					lcDebug::write("Error", "The uploaded file : ".$fileInfo['name'] ." is not an image.");
					return false;
				}
			}
			else
			{
				lcDebug::write("Error", "The file can't be uploaded see debug.");
				return false;
					
			}
		}
		return false;
	}

	public function getFromHttp($http, & $contentAttribute)
	{
		$httpFile = lcHttpFile::getInstance();
		$fieldName = "field_".$contentAttribute->attribute('identifier')."_".self::DATATYPE;
		if ($httpFile->canUplodatFile($fieldName))
		{
			$fileInfo = $httpFile->uploadedFileInfos($fieldName);
			$cleanFileName = lcStringTools::makeNormName($fileInfo['name']);
			$infos = pathinfo($cleanFileName);
			$fileInfo['created'] = date("d-m-Y",time());

			$this->value->setFileInfos($fileInfo);
			
			$filePath = $this->value->path();
			// seeking for file with the same name.
			// if found, the file Name is completed with a number.
			
			$index = 1;
			$fileName = $infos['filename'];
			$ext = $infos['extension'];
			while (file_exists($filePath."/".$cleanFileName))
			{
				$fileName = $infos['filename']."_$index";
				$cleanFileName = "$fileName.$ext";
				$index++;
			}
			$fileInfo['filename'] = $cleanFileName;
			$fileInfo['basename'] = $fileName;
			$fileInfo['extension'] = $ext;
			$fileInfo['created'] = date("d-m-Y",time());

			$this->value->setFileInfos($fileInfo);
						
			$this->storeUploadedFile($fileInfo['tmp_name'], $filePath, $cleanFileName);
							
			$contentAttribute->setAttribute('ltxt_value',$this->value->getXmlFileInfo());
		}
			
	}

	public function path(& $contentAttribute)
	{
		
	}

	/**
	 *
	 * Store uploade file to the $filepath.
	 * Tries to create the directory if it doesn't exist.
	 * @param string $tmpFileName
	 * @param string $filePath
	 * @param string $fileName
	 * @return boolean
	 */
	public function storeUploadedFile($tmpFileName, $filePath,$fileName)
	{
		if (!is_dir($filePath))
		{
			mkdir($filePath,0775,true);
		}
		if(move_uploaded_file($tmpFileName, $filePath."/".$fileName))
		{
			return true;
		}
		else
		{
			lcDebug::write("Error", "An error has occured while moving uploaded file : $fileName to directory : $filePath");
			return false;
		}
	}

	public function setValue($value,& $contentAttribute)
	{
		$contentAttribute->setAttribute('ltxt_value',$value);
		$this->value = 	new lcImage($value);
	}

	public function getValue(& $contentAttribute)
	{
		return new lcImage($contentAttribute->attribute('ltxt_value'));
	}
	
	public function getHandler()
	{
		return new lcImageHandler($this->value);
	}

	public function __toString()
	{
		return $this->value;
	}

	public function remove(& $contentAttribute)
	{
		$this->value->remove();
	}
	
	public function content()
	{
		return new lcImageHandler($this->value);
	}
	
	public function hasContent()
	{
		return ($this->value instanceof lcImage and $this->value->hasFile())?true:false;	
	}




}