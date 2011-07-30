<?php

/*!
   \class lcRule lcrule.php
   \version 1.0
   \author Jean-Luc Chassaing
   
   handels access rules.
    
 
 */
class lcRule extends lcPersistent
{
    /*!
     class constructor
     \param array $row list of attributes to be set according to the 
     static attributes defined in the static definition method.
     */
	public function __construct($row = null)
	{
		$this->definition = self::definition();
		if (is_array($row))
		{
			foreach ($row as $key=>$value)
			{
				if ($key == "params" and is_string($value))
				{
					$value = unserialize($value);
				}
				$this->setAttribute($key, $value);

			}
		}
	}

	/*!
	 persistent attributes definition
	 \return array
	 */
	public static function definition()
	{
		return array('tableName'=>'rules',
					 'className'=>'lcRule',
					 'fields' => array('id'=> array('type'=> 'integer'),
									   'role_id' => array('type' => 'integer'),
									   'module' => array('type' => 'string'),
									   'function' => array('type' => 'string'),
									   'params' => array('type' => 'string')
		),
					 'key' => 'id'
					 );
	}


	/*!
	 store object
	 */
	public function  store()
	{
		if (is_array($this->params))
		{
			$this->params = Serialize($this->params);
		}	
		parent::store();
	}
	
	/*!
	 fetch rule liste by role Id
	 \param integer $roleId
	 \return array
	 */
	public static function fetchRulesByRoleID($roleId)
	{
		$ruleList = self::fetch(self::definition(),array('role_id' => $roleId),true,null,true);
		
		return $ruleList;
		
	}


	protected $role;
	protected $id;
	protected $module;
	protected $view;
	protected $params;
}

?>