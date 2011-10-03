<?php

class lcTextblocDataType implements lcDatatypeInterface
{

	private $value;
	const DATATYPE = "textbloc";

	function __construct(& $contentAttribute)
	{
		$this->value = $contentAttribute->attribute('ltxt_value');
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
			$contentAttribute->setAttribute('ltxt_value',$attributeValue);
			$this->value = 	$attributeValue;
		}

	}


	public function setValue($value,& $contentAttribute)
	{
		$contentAttribute->setAttribute('ltxt_value',$value);
		$this->value = 	$value;
	}

	public function getValue(& $contentAttribute)
	{
		return $contentAttribute->attribute('ltxt_value');
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
		//return $this->value;


		$content = strip_tags(stripslashes($this->value));
		return $content;

	}

	public function hasContent()
	{
		return (isset($this->value) AND $this->value != "")?true:false;
	}



}