<?php


class lcAccess
{
	private static $instance;
	
	private $aAccessList;
	private $accessConf;
	
	public static function getInstance()
	{
		if (! self::$instance instanceOf lcAccess)
		{
			self::$instance = new lcAccess();
		}
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->loadAccessList();
	}
	
	public function loadAccessList()
	{
		$accessFolder = "settings/accesses";
	
		if (is_dir($accessFolder)) {
		    if ($dh = opendir($accessFolder)) {
		        while (($file = readdir($dh)) !== false) {
		        	if ($file!="." AND $file != "..")
		        	{
		        		if (is_dir($accessFolder."/".$file))
		        		{
		        			$this->aAccessList[$file] = array();
		        		}
		        	}
		        }
		    }
		}
	}
	
	public function isAccess($access)
	{
		if (isset($this->aAccessList[$access]))
		{
			return true;
		}
		return false;
	}
	
	public function getAccessFromRequestArray(& $requestArray)
	{
		
		if (count($requestArray) > 0)
		{
			$access = $requestArray[0];
			if ($this->isAccess($access))
			{
				return $access;
			}
		}
		else 
			return false;
	}
	
	
}

?>