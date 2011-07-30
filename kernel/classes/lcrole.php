<?php

/*!
  
 \class lcRole lcrole.php
  \version : 0.1
  
  This class gives all the toole to manage roles
  a role has a name and is a set of rules
  
  \author jlchassaing
 
 */

class lcRole extends lcPersistent
{
	/*!
	 
	 Class construction
	 \param array $row
	 */
	public function __construct($row = null)
	{
		$this->definition = self::definition();
		if (is_array($row))
		{
			foreach ($row as $key=>$value)
			{
				
				$this->setAttribute($key, $value);
				
			}
		}
		$this->getRules();
	}
	
	/*!
	 
	 Class definition
	 \return array
	 */
	public static function definition()
	{
		return array('tableName'=>'roles',
					 'className'=>'lcrole',
					 'fields' => array('id'=> array('type'=> 'integer'),
									   'name' => array('type' => 'string')
									  ),
					 'key' => 'id'
					);
	}

	/*!
	 
	 static function to fetch all the roles
	 if $list is false then if only one result is returned then il will be directly returned
	 otherwise an array will allways be returned
	 \param boolean $list
	 */
	public static function getRoles($list = false)
	{
		$roles = self::fetch(self::definition(),null,true,null,$list);
		if ($roles instanceOf lcRole)
		{
			return array($role);
		}
		else
		{
			return $roles;
		}
	}
	
	/*!
	 
	 fetch role by id 
	 \param int $roleId
	 \return lcRole
	 */
	public static function fetchById($roleId)
	{
		$role = self::fetch(self::definition(),array('id'=>$roleId),true,null);
		if ($role instanceof lcRole)
		{
			return $role;
		}
		else
		{
			return false;
		}
	}
	
	/*!
	 
	 Load all the rules of the loaded role
	 */
	public function getRules()
	{
		$this->rules = lcRule::fetchRulesByRoleID($this->id);
	}
	
/*! get attribute value if $name is declared in definition
	  \param string $name
	  \return mixed
	 */
	public function attribute($name)
	{
	    
		if (isset($this->definition['fields'][$name]))
		{
			return $this->$name;
		}
		elseif (isset($this->$name))
		{
		    return $this->$name;
		}
	}
	
	/*! set attribute value
	  \param string $name name of the value
	  \param mixed $value new value to set 
	 */
	
	public function setAttribute($name,$value)
	{
		if (isset($this->definition['fields'][$name]))
		{
			if ($this->definition['fields'][$name]['type'] == 'integer')
			{
				$this->$name = (int) $value;
			}
			else
			{
				$this->$name =$value;
			}
		}
		else 
		{
		    $this->$name = $value;    
		} 
		
	}
	
	/*!
	  chek if the specified rolename exists.
	  \param string $name
	 */
	public static function roleExists($name)
	{
	    $db = lcDB::getInstance();
	    $query = "select count(*) as nb from roles WHERE name='$name'";
	    $res = $db->arrayQuery($query);
	    if ($res[0]['nb'] > 0)
	    {
	        return true;
	    }
	    else
	    {
	        return false;
	    }
	}
	
	/*!
	 build a new role id
	 */
	public static function addRole($roleName)
	{
	    $row = array('name' => $roleName);
	    return new self($row);
	}
	
	
	
	
	
	
	protected $name;
	protected $id;
	
	private $rules;
}

?>