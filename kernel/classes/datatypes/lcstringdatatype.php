<?php


class lcStringDataType implements lcDatatypeInterface
{

	private $value;
	const DATATYPE = "string";

	function __construct(& $contentAttribute)
	{
		$this->value = $contentAttribute->attribute('txt_value');
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
			$contentAttribute->setAttribute('txt_value',$attributeValue);
			$this->value = 	$attributeValue;
		}
			
	}


	public function setValue($value,& $contentAttribute)
	{
		$contentAttribute->setAttribute('txt_value',$value);
		$this->value = 	$value;
	}

	public function getValue(& $contentAttribute)
	{
		return $contentAttribute->attribute('txt_value');
	}

	public function __toString()
	{
		return $this->value;
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