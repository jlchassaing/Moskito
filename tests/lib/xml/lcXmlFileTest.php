<?php

include_once 'autoload.php';
Autoloader::registerAutoloaderFunctions();

class lcXmlFileTest extends PHPUnit_Framework_TestCase
{
	public function testXmlToArray()
	{
		$xmlstring = "
		<test>
			<field>testfield</field>
			<field2 attrib=\"testattrib\">testfield2</field2>
			<field3>
				<subfield>
					<value1>value1</value1>
					<value2>value2</value2>
					<value3 attib3=\"testattrib3\">value3</value3>
				</subfield>
			</field3>
		</test>";
		$xml = simplexml_load_string($xmlstring);
		
		$resultArray=array( "field"=>array("value"=>"testfield"),
						    "field2"=>array(
						           		   "value"=>"testfield2",
						           		   "attributes"=>array("attrib"=>"testattrib")
						           		   ),
						    "field3" => array(
						           		   "childrens" => array("subfield" => array("childrens" => array("value1"=>array("value"=>"value1"),
												           		   										   "value2"=>array("value" => "value2"),
												           		   										   "value3"=>array("value"=> "value3",
												           		   														   "attributes" => array("attib3" => "testattrib3")
												           		   														  )
												           		   										  )
																					)
						           		   					  )
						           		   )
						           		
						   );
        $res = lcXmlFile::xmlToArray($xml);
		$this->assertEquals($resultArray,$res);
	}
}
