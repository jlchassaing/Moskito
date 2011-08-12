<?php


/*!

 \class lcPersistent lcpersistent.php

 $params attribute stores all the objects attributes
 they match the table fields.

 \author Jean-Luc Chassaing

 */
class lcPersistent
{
	private $params;
	protected $definition;

	public function __construct($row = null, $def = null)
	{
	    $this->definition = $def;
	}


	/*! Get definition fields
	  \return array
	 */
	public function fields()
	{
		return $this->definition['fields'];
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
	}

	public static function remove($def,$cond = null)
	{
		$tableName = $def['tableName'];
		$db = lcDB::getInstance();
		if (!is_null($cond))
		{

			$conds = " WHERE " . $db->buildCond($cond);
		}
		$query = "DELETE FROM $tableName" . $conds;

		$db->query($query);


	}

	/*!static fetch function builds the query
	 base on the object definition and the $cond param
	 $cond = array('field' => 'value')
	 actualy it only deales with the '=' test
	 *
	 \param array $def
	 \param array $cond
	 */
	public static function fetch($def,$cond = null,$asObject=false, $order = null,$list=false)
	{
		$db = lcDB::getInstance();
		$tableName = $def['tableName'];
		$query = "SELECT * FROM ";
		$conds = "";
		$orders = "";
		if (!is_null($cond))
		{
			$conds = " WHERE " . $db->buildCond($cond);
		}
		if (!is_null($order))
		{

			$orders = " ORDER BY ".$db->buildSort($order);

		}

		$query = $query . " ".$tableName .$conds.$orders;
		$aQueryResult =  $db->arrayQuery($query);
		if ($aQueryResult !== false)
		{
		    if (count($aQueryResult) == 1 and !$list)
		    {
		        if ($asObject)
		        {
		            return new $def['className']($aQueryResult[0]);
		        }
		        else
		        {
		            return $aQueryResult[0];
		        }
		    }
		    else
		    {
		        if ($asObject)
		        {
		            $aObList = array();
		            foreach ($aQueryResult as $value)
		            {
		                $aObList[] = new $def['className']($value);
		            }
		            return $aObList;
		        }
		        else
		        {
		            return $aQueryResult;
		        }
		    }
		}
		else
		{
		    return false;
		}

	}




	/*!
	 set the objects attributes based on
	 the row definition. Each field matches
	 a table field.
	 \param array $def
	 \param array $row
	 */
	public function setdatas($def,$row)
	{
		foreach ($row as $key=>$value)
		{
			if (isset($def['fields'][$key]))
			{
				if ($def['fields'][$key]['type'] == 'integer')
				{
					$value = (int) $value;
				}
			}
			$this->params[$key] = $value;
		}
	}

	public function store()
	{
		$db = lcDB::getInstance();
		$query = "";
		$where = "";
		$newInsert = false;
		$tableKey = $this->definition['key'];
		$keyValue = $this->attribute($tableKey);
//		$keyValue2 = $this->$tableKey;
		if (isset($keyValue) and is_integer($keyValue))
		{
			$query  = "UPDATE ";
			$where = " WHERE $tableKey=$keyValue";
		}
		else
		{
			$query = "INSERT INTO ";
			$newInsert = true;
		}

		$fields = "";
		$comma = "";
		$nbFields = count($this->definition['fields']);
		$fieldId = 0;
		foreach ($this->definition['fields'] as $key=>$value)
		{
			if ($key != $tableKey)
			{
				if ($fieldId != 0 AND $fields != "")
				{
					$comma = ", ";
				}
				if ($value['type'] == 'string')
				{
					$fields .= $comma."$key = '".lcDB::cleanData($this->attribute($key))."'";
				}
				else
				{
					$fieldValue = $this->attribute($key);
					if (!is_null($fieldValue))
					$fields .= $comma."$key = ".$this->attribute($key);
				}

			}
			$fieldId++;
		}
		$query = $query .$this->definition['tableName'] .
				 " SET " . $fields . $where;
		$db->begin();
		lcDebug::write("NOTICE", "query : $query");
		$db->query($query);
		$lastId = $db->lastId();
		$db->commit();
		if ($newInsert)
		{
			return $lastId;
		}
		else
		{
			return true;
		}

	}



	public function makeNormName($name)
	{
		return lcStringTools::makeNormName($name);
	}

	public function init($def)
	{
		foreach ($def['fields'] as $key=>$value)
		{
			$this->params[$key] = null;

		}
	}

}