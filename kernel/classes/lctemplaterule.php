<?php


/**
 * 
 * Template Rule class manager
 * helps to set a specific template according to rules
 * as node position or contentobjet id or class_identifier
 * @author jlchassaing
 *
 */
class lcTemplateRule
{
	/**
	 * 
	 * lcTemplateRule singleton
	 * @var lcTemplateRule
	 */
	private static $instance;
	
	
	/**
	 * 
	 * Set of rules
	 * @var array
	 */
	private $Rules;
	
	
	/**
	 * 
	 * Singleton caller
	 * @return lcTemplateRule
	 */
	public static function getInstance()
	{
		if (!self::$instance instanceOf lcTemplateRule)
		{
			self::$instance = new lcTemplateRule();
		}
		return self::$instance;
		
	}
	
	/**
	 * 
	 * Constructor 
	 * loads the template rules defined in the loaded access
	 */
	private function __construct()
	{
		$templateRulePath = "settings/accesses/".$GLOBALS['SETTINGS']['currentAccess']."/templateRules.ini";
		$Rules = array();
	
		$this->Rules = parse_ini_file($templateRulePath,true);	
	}
	
	
	/**
	 * 
	 * returns a template Path according to the rules if a matching rule is found 
	 * 
	 * @param array $rules
	 * @return string
	 */
	public function getTemplate(array $rules)
	{
		$templatePath = "";
		$matchRule = false;
		foreach ($this->Rules as $key=>$value)
		{
			foreach ($rules as $ruleKey=>$ruleValue)
			{
				if (isset($value['Match'][$ruleKey])) 
				{
					if ($value['Match'][$ruleKey] == $ruleValue)
					{
						$matchRule = true;	
					}
					else
					{
						$matchRule = false;
					}	
				}
			}
			if ($matchRule)
			{
				$templatePath =$value['Template'];
				break;
			}
		}
		
		
		return $templatePath;
	}
	
	
}



?>