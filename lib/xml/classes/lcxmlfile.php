<?php
/**
 *
 * Xml manipulation tools
 * @author Jean-Luc Chassaing
 *
 */

class lcXmlFile
{
	/**
	 *
	 * read a xmlfile and returns it's content as an array
	 * @param string $xmlFile
	 * @return array
	 */
	public static function readFile($xmlFile)
	{
		if (file_exists($xmlFile))
		{
			/*$xml = simplexml_load_file($xmlFile);
			 $arrayContent = array();
			 $arrayContent = self::xmlToArray($xml);*/
				
			$dom = new DOMDocument( '1.0', 'utf-8' );
			$success = $dom->load( $xmlFile );
			$root = $dom->documentElement;
			$arrayContent = self::buildArray($root);
				
			return $arrayContent;
		}
		else
		{
			lcDebug::write("ERROR", "The xmlFile : $xmlFile could not be loaded !");
			return false;
		}
	}


	public static function buildArray($node)
	{
		
		if ($node->hasChildNodes())
		{
			foreach ($node->childNodes as $child)
			{
				if ($child->nodeType == XML_ELEMENT_NODE)
				{
					if ($child->hasChildNodes())
					{
						$array[$child->nodeName] = self::buildArray($child);
					}
					else
					{
						echo $node->nodeName. "->" . $child->nodeValue."<br />";
						$array[$node->nodeName] = $child->nodeValue;	
					}
					
					if ($child->hasAttributes())
					{
						foreach ($child->attributes as $attr)
						{
							$array[$child->nodeName]['attributes'][$attr->name] = $attr->value;
						}
					}
						
				}
				else 
				{
					if (trim($child->wholeText) != "")
					{
						$array[$node->nodeName] = $child->wholeText;
					}
					
				}

			}
			return $array;
		}
		else {
			
			return $node->nodeValue;
		}
		
	}

	/**
	 *
	 * recursively read the xml content
	 * @param SimpleXmlElement $xml
	 */
	public static function xmlToArray($xml)
	{
		$returnArray = array();

		if (count($xml) > 0)
		{
			foreach ($xml as $key=>$value)
			{

				foreach ($value->attributes() as $attrKey=>$attrValue)
				{
						
					$returnArray[ strval($key)]["attributes"]= array($attrKey => strval($attrValue));
				}
				if (count($value) == 1)
				{
					$returnArray[$key] = self::xmlToArray($value);
						
				}
				elseif(count($value) > 1)
				{
					$returnArray[] = self::xmlToArray($value);
						
				}
				else {
					$returnArray[$key] = strval($value);
				}
			}
		}

		return $returnArray;
	}


}



?>