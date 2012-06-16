<?php

/**
 *
 * Class to help with file upload
 * @author jlchassaing
 *
 */
class lcHttpFile
{
	/**
	 *
	 * Singelton private static variable
	 * @var lcHttpFile
	 */
	private static $instance;


	/**
	 *
	 * Singleton caller
	 * @return lcHttpFile
	 */
	public static function getInstance()
	{
		if (!self::$instance instanceOf lcHttpFile)
		{
			self::$instance = new lcHttpFile();
		}
		return self::$instance;
	}

	/**
	 *
	 * Checks if the file included in the HTTP POST Filed name $filedName
	 * has been correctly uploaded.
	 *
	 * If an upload errr has occrued, it will be displayed by the displayUploadError method.
	 * @param string $fieldName
	 * @return boolean
	 */
	public function canUplodatFile($fieldName)
	{
		if (isset($_FILES[$fieldName]))
		{
			if ($_FILES[$fieldName]['error'] == UPLOAD_ERR_OK)
			{
				return true;
			}
			else
			{
				$this->dislplayUploadError($_FILES[$fieldName]['error']);
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 *
	 * Test if the filedName is an uploaded FIle
	 * @param string $fieldName
	 * @return boolean
	 */
	public function isUpload($fieldName)
	{
		if (isset($_FILES[$fieldName]))
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
	 * Display Error message according to the error Code
	 * @param int $errorNo
	 *
	 */
	private function dislplayUploadError($errorNo)
	{
		switch ($errorNo)
		{
			case UPLOAD_ERR_CANT_WRITE:
				lcDebug::write("ERROR", "The uploaded file can't be written on disk.");
			break;
			case UPLOAD_ERR_EXTENSION:
				lcDebug::write("ERROR", "A PHP extension stoped the upload. Check php settings.");
			break;
			case UPLOAD_ERR_FORM_SIZE:
				lcDebug::write("ERROR", "The uploaded file is biger than the MAX_FILE_SIZE value specified in the form.");
			break;
			case UPLOAD_ERR_INI_SIZE:
				lcDebug::write("ERROR", "The uploaded file is biger than the upload_max_filsize specified in php settings.");
			break;
			case UPLOAD_ERR_NO_FILE:
				lcDebug::write("ERROR", "No file was uploaded.");
			break;
			case UPLOAD_ERR_NO_TMP_DIR:
				lcDebug::write("ERROR", "Missing temporary folder.");
			break;
			case UPLOAD_ERR_PARTIAL:
				lcDebug::write("ERROR", "The Uploaded file was only partialy uploade.");
			break;
			default:
				;
			break;
		}
	}

	/**
	 *
	 * return all uploaded file datas : name, type, size
	 * assuming that befor calling this method, the canUploadFile method has been called
	 * @param string $fieldName
	 * @return array
	 */
	public function uploadedFileInfos($fieldName)
	{
		$aFileInfo = array();
		$aFileInfo['name'] = $_FILES[$fieldName]['name'];
		$aFileInfo['type'] = $_FILES[$fieldName]['type'];
		$aFileInfo['size'] = $_FILES[$fieldName]['size'];
		$aFileInfo['tmp_name'] = $_FILES[$fieldName]['tmp_name'];
		$infos = pathinfo($aFileInfo['name']);
		$aFileInfo['extension'] = $infos['extension'];
		$aFileInfo['filename'] = $infos['filename'];

		return $aFileInfo;

	}





}