<?php
/*!
 \class Autoloader autoloader.php
 \author jean-luc chassaing
 \version 1.0
 dealse with the autoloading of class.
 */

class Autoloader
{
    private static $autoloadSections = array("kernel","lib");
	/*!
	 Register the autoload method
	 */
	public static function registerAutoloaderFunctions()
	{
	    $canLoad = true;
	    if (!file_exists("var/"))
	    {
	        if (!@mkdir("var",0775))
	        {
	            $canLoad = false;
	        }
	    }
	    if (is_writable('var/'))
	    {
	        spl_autoload_register(array('Autoloader','classLoader'));
	    }
	    else
	    {
	        $canLoad =  false;
	    }
	    return $canLoad;

	}

	/*!
	 Singleton
	 */
	private static $instance;

	/*!
	 Autoload Array
	 */
	private $classAutoload;



	/*!

	 Autoloader singleton
	 \return Autoloader
	 */
	public static function getInstance()
	{
		if (!self::$instance instanceof Autoloader)
		{
			self::$instance = new Autoloader();
		}
		return self::$instance;
	}

	/*!
	 Constructor
	 */
	private function __construct()
	{

		$this->classAutoload = array();
		foreach (self::$autoloadSections as $section)
		{
			$filePath = "var/".$section."_autoload.php";
			if (file_exists($filePath))
			{
				$sectionAutoloadArray = require $filePath;
				$this->classAutoload = array_merge($this->classAutoload,$sectionAutoloadArray);
			}
		}
	}

	/*!
	 Checks if a class exists in the autoload array
	 \return boolean

	 */
	public function classExistsInAutoload($className)
	{
		return isset($this->classAutoload[$className]);
	}

	/*!
	 return the path of a class from the autoload Array
	 */
	public function getClassPathFromAutoload($className)
	{
		return $this->classAutoload[$className];
	}

	/*!
	 Search for the class path base on the class name
	 \return array is an associative array keys are path and dir
	 */
	public function findClass($className)
	{
		$fileName = strtolower($className).".php";
		$sectionNumber = count(self::$autoloadSections);
		$i=0;
		$startDir = self::$autoloadSections[$i];
		$classPath = false;
		while (($classPath = $this->searchFileInDir($startDir, $fileName)) == null)
		{
			$i++;
			if ($i < $sectionNumber)
			{
				$startDir = self::$autoloadSections[$i];
			}
		}
		return array("path" => $classPath, "dir" => $startDir);
	}

	/*!
	 Static class loader method registered in the spl_autoload
	 */
	public static function classLoader($className)
	{
		$autoloader = self::getInstance();
		if ($autoloader->classExistsInAutoload($className))
		{
			$classPath["path"] = $autoloader->getClassPathFromAutoload($className);
		}
		else
		{
			$classPath = $autoloader->findClass($className);
			if ($classPath AND file_exists($classPath["path"]))
			{
				$autoloader->registerClassPathInFile($className, $classPath);
			}
		}
		require_once $classPath["path"];
	}

	/*!
	 Write the class path found in the correct autoload file.
	 */
	private function registerClassPathInFile($className,$classPath)
	{
		$filename = "var/".$classPath["dir"]."_autoload.php";
		$fileArray = array();
		if ( file_exists($filename) AND filesize($filename) != 0){
			$fileArray = require $filename;
		}
		$fileArray[$className] = $classPath["path"];

		$file = fopen($filename, 'w');
		$entete = "<?php\n" .
			 	  "return array(\n";
		$footer = ");\n" .
				  "?>\n";
		fwrite($file, $entete);
		$i =1;
		$limit = count($fileArray);
		foreach ($fileArray as $key=>$value)
		{
			$line = "    \"$key\" => \"$value\"";
			if ($i < $limit)
			{
				$line = $line .", ";
			}
			$line .= "\n";
			fwrite($file, $line);
		}
		fwrite($file, $footer);
		fclose($file);
	}

	/*!
	 recursively find a class definition file
	 \return mixed
	 */
	private function searchFileInDir($startDir, $filename)
	{
		$result = false;
		if (is_dir($startDir)) {
		    if ($dh = opendir($startDir)) {
		        while (($file = readdir($dh)) !== false) {
		            if ($file != "." AND $file != ".."){
		            	$path = $startDir."/".$file;
			            if ( $file == $filename )
			            {
			            	return $path;
			            }
			            else {
				            if (is_dir( $path)){
				            	$result = $this->searchFileInDir($path, $filename);
				            	if ($result){
				            		return $result;
				            	}
				            }
			            }
		            }

		        }
		        closedir($dh);
		    }
		}
	}
}