<?php

/*!

 \class lcImageHandler lcimagehandler.php
 \version  0.1
 *
 Helps manage with images and different aliases of images
 *
 \author Chassaing Jean-Luc
 *
 */
class lcImageHandler
{

    private $image;
    private $aliasImagePath;
    private $aliasSettings;

    /*!
     *
     Image handler
     \param lcImage $image
     */
    function __construct(& $image)
    {
        $this->image = $image;
        $this->aliasSettings = false;
    }

    public function get($alias)
    {
        if (!$this->aliasExists($alias))
        {
            $this->convert($this->image->fullPath(),$this->aliasImagePath,$alias);
        }
        else
        {

        }
        return lcHTTPTool::buildUrl("/".$this->aliasImagePath,true,false);

    }

    public function buildAliasPath($alias)
    {
        $splitedDate = explode("-",$this->image->attribute('created'));

        $aliasFileName = $this->image->attribute('filename')."_".$alias.".".$this->image->attribute('extension');
        $settings = lcSettings::getInstance();
        $storageDir = $settings->value('FileSettings','StorageDir');
        $rootDir = $GLOBALS['SETTINGS']['siteRootDir'];
        $this->aliasImagePath = $this->image->path() ."/". $aliasFileName;
    }

    public function aliasExists($alias)
    {

        $filePath = $this->image->fullPath();
        if ($alias != "original")
        {
            $this->buildAliasPath($alias);
            $filePath = $this->aliasImagePath;
        }

        if (file_exists($filePath))
        {
            $aliasSettings = $this->getAliasSettings($alias);
            $imageInfo = getimagesize($filePath);
            $return = true;
            if ($imageInfo[0] > $imageInfo[1])
            {
                if ($imageInfo[0] != $aliasSettings['width'])
                {
                     $return = false;
                }
            }
            else
            {
                if ($imageInfo[1] != $aliasSettings['height'])
                {
                    $return = false;
                }
            }

            if (!$return)
            {
                unlink($filePath);

            }
            return $return;

        }
        else
        {
            return false;
        }
    }

    public function getAliasSettings($alias)
    {

        if (!isset($this->aliasSettings[$alias]))
        {

            $settings = lcSettings::getInstance("image");
            if ($settings->hasValue("AliasList", "AliasArray"))
            {
                $aliasList = $settings->value("AliasList", "AliasArray");
                if (in_array($alias, $aliasList))
                {
                    if ($settings->hasGroup($alias))
                    {
                        $this->aliasSettings[$alias]['filter'] = $settings->value($alias,"Filter");
                        $this->aliasSettings[$alias]['params'] = $settings->value($alias,"Param");
                        $imageSize = explode("x",$this->aliasSettings[$alias]['params']);
                        $this->aliasSettings[$alias]['width'] = (int) $imageSize[0];
                        $this->aliasSettings[$alias]['height'] = (int) $imageSize[1];

                    }
                }
            }
        }

        return $this->aliasSettings[$alias];
    }

    public function convert($sourceImage,$destImage,$alias)
    {
        $settings = lcSettings::getInstance("image");
        $aliasSettings = $this->getAliasSettings($alias);
        $options = "-{$aliasSettings['filter']} {$aliasSettings['params']}";

        $convertBin = $settings->value("Converter","ConvertBin");

        $command =  $convertBin. " " . $sourceImage." ". $options ." ". $destImage;

        $descriptors = array(
        array( 'pipe', 'r' ),
        array( 'pipe', 'w' ),
        array( 'pipe', 'w' ),
        );

        // Open ImageMagick process
        $imageProcess = proc_open( $command, $descriptors, $pipes );
        // Close STDIN pipe
        fclose( $pipes[0] );

        $errorString  = '';
        $outputString = '';
        // Read STDERR
        do
        {
            $outputString .= rtrim( fgets( $pipes[1], 1024 ), "\n" );
            $errorString  .= rtrim( fgets( $pipes[2], 1024 ), "\n" );
        } while ( !feof( $pipes[2] ) );

        // Wait for process to terminate and store return value
        $status = proc_get_status( $imageProcess );
        while ( $status['running'] !== false )
        {
            // Sleep 1/100 second to wait for convert to exit
            usleep( 10000 );
            $status = proc_get_status( $imageProcess );
        }
        $return = proc_close( $imageProcess );

        // Process potential errors
        // Exit code may be messed up with -1, especially on Windoze
        if ( ( $status['exitcode'] != 0 && $status['exitcode'] != -1 ) || strlen( $errorString ) > 0 )
        {
            lcDebug::write('Error', "The command '{$command}' resulted in an error ({$status['exitcode']}): '{$errorString}'. Output: '{$outputString}'");
        }
        // Finish atomic file operation
        // copy( $this->getReferenceData( $image, 'resource' ), $this->getReferenceData( $image, 'file' ) );
    }


}