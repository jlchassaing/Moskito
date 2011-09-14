<?php

/*!

\class lcRole lcrole.php
\version  0.1

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
        $roles = self::fetch(self::definition(),null,null,null,null,true,$list);
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
        $role = self::fetch(self::definition(),array('id'=>$roleId),null,null,null,true,null);
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
     retrieve a role from its name
    \param string $name role name
    \return lcRole
    */
    public static function fetchByName($name)
    {
        $cond = array('name' => $name);
        return self::fetch(self::definition(),$cond,null,null,null,true,false);
    }

    public function hasAccessTo(& $module)
    {
        $hasAccess = false;
        if (isset($this->rules[0]))
        {
            if ($this->rules[0]->attribute('module') == 'all' and $this->rules[0]->attribute('function') == 'all')
            {
                $hasAccess = true;
            }
            else
            {
                foreach ($this->rules as $rule)
                {
                    if ($rule->attribute('module') == $module->module)
                    {
                        if ($rule->attribute('function') == $module->functionName())
                        {
                            $RuleParams = $rule->attribute('params');
                            if (is_array($RuleParams))
                            {
                                $viewFunctionParams = $module->getFunctionViewParams();
                                foreach ($RuleParams as $key=>$value)
                                {
                                    $list = $viewFunctionParams[$key][0]::$viewFunctionParams[$key][1]();
                                    if (isset($module->params['NodeId']))
                                    {
                                        if (lcContentMenu::hasSection($value,$module->params['NodeId']))
                                        {
                                            $hasAccess = true;
                                        }
                                    }

                                }
                            }

                        }
                        else
                        {
                            $hasAccess = true;
                        }
                    }
                }
            }
        }


        return $hasAccess;
    }

    public function delete()
    {
       lcRule::remove(lcRule::definition(),array('role_id' => $this->id));

        parent::remove(self::definition(),array('id'=> $this->id));
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