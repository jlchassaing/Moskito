<?php

/*!
 * \version 0.1
 * \author jlchassaing

 * Access class manager
 */
class lcAccess
{
	public static $instance;

	private $aAccessList;
	private $aAccessHostConf;
	private $accessConf;
	private $accessType;
	private $defaultAccess;
	private $currentAccess;
	private $mode;


	const HOST = 1;
	const URI = 2;

	/*!
	 * singleton
	 * \retunr lcAccess
	 *
	 */
	public static function getInstance()
	{
		if (!self::$instance instanceOf lcAccess)
		{
			self::$instance = new self();
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

		// load settings config
		$iniAccessRules = lcSettings::getValue('AccessSettings', 'AccessRules', 'settings/settings.ini');
		if (is_array($iniAccessRules))
		{
		    foreach ($iniAccessRules as $uri=>$access)
		    {
		        if (isset($this->aAccessList[$access]))
		        {
		          $this->aAccessHostConf[$uri] = $access;
		        }
		    }
		}

		 $this->defaultAccess = lcSettings::getValue("AccessSettings", "Default", 'settings/settings.ini');

		 $this->mode = lcSettings::getValue("AccessSettings", "AccessMode", 'settings/settings.ini');
		 $this->mode = explode(",",$this->mode);
	}

	public function isAccess($access)
	{
		if (isset($this->aAccessList[$access]))
		{
			return true;
		}
		return false;
	}

	/*!
	 * load the current access from the requestArray
	 * this request array
	 */
	public function getAccessFromRequestArray(& $requestArray)
	{
	    $access = null;
	    // test if the hosts is registered in the hostAccess array
	    if (isset($requestArray['host']) and isset($this->aAccessHostConf[$requestArray['host']]))
	    {
	        $access = $this->aAccessHostConf[$requestArray['host']];
	        $this->accessType = self::HOST;
	    }
	    if (count($requestArray['request']) > 0)
	    {
	        // get access from uri

	        if ($this->isAccess($requestArray['request'][0]))
	        {
	            $access = $requestArray['request'][0];
	            $this->accessType = self::URI;
	        }
	    }
	    if ($access === null)
	    {
	        // load default access
	        $access = $this->defaultAccess;
	        $this->accessType = self::URI;
	    }
        lcDebug::write("Notice", "Selected access : $access");
	    $this->currentAccess = $access;
	    return $access;

	}

	public function access()
	{
	    return $this->currentAccess;
	}

	/*!
	 * geter function fetch the parameter identified by $name
	 * \param string $name
	 * \return mixed
	 */
	public function __get($name)
	{
	    return $this->$name;
	}

	/*!
	 * setter sets the parameter identified by $name with $value value
	 * \param string $name
	 * \param mixed $value
	 */
	public function __set($name,$value)
	{
	    $this->$name = $value;
	}


}

?>