<?php


class lcFileDataType implements lcDatatypeInterface
{

    private $value;

    const DATATYPE = "file";

    function __construct(& $contentAttribute)
    {

        $this->value = new lcFile($contentAttribute->attribute('ltxt_value'));



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
                if ($type[0] != "image")
                {
                    return true;
                }
                else
                {
                    lcDebug::write("Error", "The uploaded file : ".$fileInfo['name'] ." is not a file.");
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

            $fileInfo['created'] = date("d-m-Y",time());

            $this->value->setFileInfos($fileInfo);

            $this->value->setFileInfos($fileInfo);
            $contentAttribute->setAttribute('content',$this);
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
        $this->value = 	new lcFile($value);
    }

    public function getValue(& $contentAttribute)
    {
        return new lcFile($contentAttribute->attribute('ltxt_value'));
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
        return $this->value;
    }

    public function hasContent()
    {
        return ($this->value instanceof lcFile and $this->value->hasFile())?true:false;
    }

    public function publish(& $contentObject, & $contentAttribute)
    {

        $objectName = $contentObject->attribute('object_name');
        $fileInfo = $this->value->fileInfo();
        if (isset($fileInfo['tmp_name']))
        {
            $fileName = lcStringTools::makeNormName($objectName);
            $fileName = $this->value->searchSameName($fileName);
            $this->value->setFileName($fileName);
            $filePath = $this->value->path();

            $this->storeUploadedFile($fileInfo['tmp_name'], $filePath, $fileName);

            $this->value->unsetValue('tmp_name');
            $this->value->setFileInfos();

            $contentAttribute->setAttribute('ltxt_value',$this->value->getXmlFileInfo());
        }

    }




}