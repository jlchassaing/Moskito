<?php

class lcXmlblocDataType implements lcDatatypeInterface
{

	private $value;
	const DATATYPE = "xmlbloc";

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

		$allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
		$allowedTags.='<li><ol><ul><span><div><br><ins><del>';
		$content = strip_tags(stripslashes($this->value),$allowedTags);
		return $content;

	}

	public function hasContent()
	{
		return (isset($this->value) AND $this->value != "")?true:false;
	}

public function publish(& $contentObject, & $contentAttribute)
    {

    }



}