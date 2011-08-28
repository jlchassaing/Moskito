<?php

class lcContentClass
{

	private function __construct($classIdentifier)
	{
		$def = self::loadClassDefinition($classIdentifier);
		if ($def)
		{
			$this->contentClassIdentifier = $classIdentifier;
			$this->name = $def['ClassName'];
			$this->nameingRule = $def['NamingField'];
			foreach ($def['Fields'] as $field)
			{
				$this->fields[$field['Identifier']] = array('identifier'=>$field['Identifier'],
															'name' 		=> $field['Name'],
															'datatype'  => $field['DataType'],
															'required'  => $field['Required'],
															'formfield' => (isset($field['FormField']))?$field['FormField']:false
				);
			}
		}


	}

	public static function getAvailableContentClasses()
	{
		$classDefdir = "classdefs/";
		$aClassIdentifiers = array();
		if (is_dir($classDefdir))
		{
			if ($dh = opendir($classDefdir))
			{
				while (($file = readdir($dh)) !== false)
				{
					if ($file != "." and $file != "..")
					{
						$tmp = explode(".",$file);
						if ($tmp[1] == "xml")
						{
							$aClassIdentifiers[] = $tmp[0];
						}
					}


				}
				closedir($dh);
			}
		}
		return $aClassIdentifiers;

	}

	public function getName()
	{
		return $this->name;
	}

	public function getFields()
	{
		return $this->fields;
	}


	public function getNamingRule()
	{
		return $this->nameingRule;
	}

	/**
	 *
	 * lcContentClass singleton
	 * @param string $classIdentifier
	 * @return lcContentClass
	 */
	public static function getInstance($classIdentifier)
	{
		if (!isset(self::$contentClass[$classIdentifier]) or !self::$contentClass[$classIdentifier] instanceOf lcContentClass)
		{
			self::$contentClass[$classIdentifier] = new lcContentClass($classIdentifier);
		}
		return self::$contentClass[$classIdentifier];


	}

	public static function loadClassDefinition($classIdentifier)
	{
		$classDefPath = "classdefs/".$classIdentifier.".xml";
		if (file_exists($classDefPath))
		{
			$dom = new DOMDocument( '1.0', 'utf-8' );
			$success = $dom->load( $classDefPath );
			$root = $dom->documentElement;
			$classDef = array();
			foreach ($root->childNodes as $node)
			{
				if ($node->nodeName == "ClassName")
				{
					$classDef['ClassName'] = $node->nodeValue;
				}
				if ($node->nodeName == "NamingField")
				{
					$classDef['NamingField'] = $node->nodeValue;
				}
				if ($node->nodeName == "Fields")
				{
					$classDef['Fields'] = array();

					foreach ($node->childNodes as $key=>$fieldDef)
					{
						if ($fieldDef->nodeName == "Field")
						{
							foreach ($fieldDef->childNodes as $field)
							{
								if ($field->nodeName != "#text")
								{
								$value = $field->nodeValue;
								if ($value == "true" or $value == "TRUE")
								    $value = true;
								elseif ($value == "false" or $value == "FALSE")
								    $value = false;
								$tmp[$field->nodeName] = $value;
								}
							}
							$classDef['Fields'][]=$tmp;
						}

					}
				}
			}

		}
		if (is_array($classDef))
		{
			return $classDef;
		}
		else
		{
			return false;
		}
	}

	private function isClassType($type)
	{
		if ($this->contentClassIdentifier == $type)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	private static $contentClass;
	private $contentClassIdentifier;
	private $name;
	private $nameingRule;
	private $fields;
}