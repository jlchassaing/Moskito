<?php

/*!
 \class lcUser lcuser.php
 \version 0.1
 \author Jean-Luc Chassaing

 handle the users

 */
class lcUser extends lcPersistent
{
	/*!
	 class constructor
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
	}

	/*!
	  class definition
	  \return array
	 */
	public static function definition()
	{
		return array('tableName'=>'users',
					 'className'=>'lcuser',
					 'fields' => array('login'=> array('type'=> 'string'),
									   'password' => array('type' => 'string'),
									   'id' => array('type' => 'integer'),
									   'role' => array('type' => 'string')
									  ),
					 'key' => 'id'
					);
	}

	/*!
	 static function test if the string is a well formated password
	 \return boolean
	 */
	public static function checkPassword($string)
	{
	    if (preg_match("#^(?=.*\w)(?!.*[\s'`\"]).*$#", $string))
	    {
	        return true;
	    }
	    else
	    {
	        return false;
	    }
	}


    /*
     static method to encode the password
     \return string encoded password
     */
	public static function encodePassword($login,$password)
	{
		return md5($login ."\n" . $password);
	}

	/*
	 Static method to get the current loged id user
	 \return lcUser
	 */
	public static function getCurrentUser()
	{
		$http = lcHTTPTool::getInstance();
		if ($http->hasSessionVariable('user_id'))
		{
			$userId = $http->sessionVariable('user_id');

			return self::getById($userId);
		}
		else
		{
		    $annonUserArray = array('login' => 'anonymous',
		                            'role' => 'anonymous');
		    return new lcUser($annonUserArray);
		}

	}

	/*!
	 static method to get user by id
	 \param integer $id
	 \return lcUser
	 */
	public static function getById($user_id)
	{
		$cond = array('id'=>$user_id);
		$user = self::fetch(self::definition(), $cond,null,null,null,true);

		return $user;
	}

	public static function getByRole($role)
	{
	    $cond = array('role' => $role);
	    $list = self::fetch(self::definition(),$cond,null,null,null,false,true);
	    return $list;
	}

	/*!

	  Get all users
	  \return array
	 */
	public static function getUsers()
	{
		$users = self::fetch(self::definition(),null,null,null,null,true);
		if ($users instanceof lcUser)
		{
			return array($users);
		}
		else
		{
			return $users;
		}
	}

	/*!
	 static method that returns true if the user can perform the module
	 \return boolean
	 */
	public function can(& $Module)
	{
		$settings = lcSettings::getInstance();
		//$currentLoadedModule = $Module->module;
		//$currentLoadedView = $Module->view;
        $hasAccess = false;

        if ($Module->module == "user" and $Module->view == "login")
        {
            $hasAccess = true;
        }
        else
        {
            $userRole = lcRole::fetchByName($this->role);
            if($userRole)
            {
                if ($userRole->hasAccessTo($Module))
                {
                    $hasAccess = true;
                }

            }
        }


        /*
		$userLogin = true;
		if ($currentLoadedModule == 'content' and ($currentLoadedView == 'view' or $currentLoadedView == 'googlesitemap'))
		{
			$userLogin = false;
		}
		elseif($currentLoadedModule == "error")
		{
			$userLogin = false;
		}

		if (!$userLogin and $settings->value("User", "login"))
		{
			$userLogin = true;
		}
		*/


		return $hasAccess;
	}



	protected $login;
	protected $id;
	protected $password;
	protected $role;
}

?>