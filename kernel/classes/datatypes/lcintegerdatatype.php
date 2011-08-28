<?php


class lcIntegerDataType implements lcDatatypeInterface
{

	private $value;

	function __construct(& $contentAttribute)
	{
		$this->value = $contentAttribute->attribute('int_value');
	}

public function canGetFromHttp($http, & $contentAttribute)
	{
		if ($http->hasPostVariable("field_".$contentAttribute->attribute('identifier')."_".self::DATATYPE))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getFromHttp($http, & $contentAttribute)
	{
		if ($http->hasPostVariable("field_".$contentAttribute->attribute('identifier')."_".self::DATATYPE))
		{
			$attributeValue = $http->postVariable("field_".$contentAttribute->attribute('identifier')."_".self::DATATYPE);
			$contentAttribute->setAttribute('int_value',$attributeValue);
			$this->value = 	$cleanValue;
		}

	}




	public function setValue($value,& $contentAttribute)
	{
		$contentAttribute->setAttribute('int_value',$value);
	}

	public function getValue(& $contentAttribute)
	{
		return $contentAttribute->attribute('int_value');
	}


	public function remove(& $contentAttribute)
	{
		return true;
	}

	public function content()
	{
		return $this->value;
	}

	public function hasContent()
	{
		return (isset($this->value))?true:false;
	}


}