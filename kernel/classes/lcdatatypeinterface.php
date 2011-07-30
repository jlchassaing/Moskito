<?php

/**
 * 
 * DataType interface declaration
 * @author jlchassaing
 *
 */
interface lcDatatypeInterface
{
	
	/**
	 * 
	 * Class Constructor
	 * @param lcContentObjectAttribute $contentAttribute
	 */
	public function __construct(& $contentAttribute);
	
	/**
	 * 
	 * Check if there is an attribute of this datatype that can be set
	 * @param lcHTTPTool $http
	 * @param lcContentObjectAttribute $contentAttribute
	 * @return boolean
	 */
	public function canGetFromHttp($http,& $contentAttribute);
	
	
	/**
	 * 
	 * Get the content from the http (post or get) to set the attribute value
	 * @param lcHTTPTool $http
	 * @param unknown_type $contentAttribute
	 */
	public function getFromHttp($http,& $contentAttribute);
	
	/**
	 * 
	 * Directly set the attribute value
	 * @param mixed $value
	 * @param lcContentObjectAttribute $contentAttribute
	 */
	public function setValue($value,& $contentAttribute);
	
	/**
	 * 
	 * Return the datatype value
	 * @param lcContentObjectAttribute $contentAttribute
	 * @return mixed
	 */
	public function getValue(& $contentAttribute);
	
	/**
	 * 
	 * Delete the content attribute
	 * @param unknown_type $contentAttribute
	 * @return boolean
	 */
	public function remove(& $contentAttribute);
	
	/**
	 * 
	 * Get the datatype content
	 */
	public function content();
	
	
	/**
	 * 
	 * Check if the attribute has a value
	 * @return boolean
	 */
	public function hasContent();
	
	
}


?>