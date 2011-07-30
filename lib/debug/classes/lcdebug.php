<?php

class lcDebug
{
	public static $adebug;
	private static $instance;

	public static function getInstance()
	{
		if (! self::$instance instanceof lcDebug)
		{
			self::$instance = new lcDebug();
		}
		return self::$instance;
	}

	private function __construct()
	{

	}

	public static function error_handler($errno, $errstr, $errfile, $errline)
	{
		if (!(error_reporting() & $errno)) {
			// Ce code d'erreur n'est pas inclus dans error_reporting()
			return;
		}
		$aDebugLine = array();
		
		switch ($errno){
			case E_USER_ERROR:
				$aDebugLine['type'] = "ERROR";
				
				break;

			case E_USER_WARNING:
				$aDebugLine['type'] = "WARNING";
				break;

			case E_USER_NOTICE:
				$aDebugLine['type'] = "NOTICE";
				break;

			default:
				$aDebugLine['type'] = "UNKNOWN";
				break;
		}
	
		$message = "";
		if ($errstr){
			$message .= $errstr;
		}
		if ($errline)
		{
			$message .= " on line $errline";
		}
		
		if ($errfile)
		{
			$message .= " in file $errfile";
		}
		$aDebugLine['message'] = $message;
		self::$adebug[] = $aDebugLine;
		/* Ne pas exécuter le gestionnaire interne de PHP */
		return true;
	}
	
	public static function write($type,$message){
		self::$adebug[]= array('type'=>$type,
							   'message' => $message);
	}
	
	
	public static function output()
	{
		$output = "";
		if (count(self::$adebug) > 0)
		{
			$output .= "<div id=\"debug\">\n";
			$output .= "<table>\n";
			$output .= "<tr><th>Type</th><th>Message</th></tr>";
			foreach (self::$adebug as $value)
			{
				$output .= "<tr><td>{$value['type']}</td><td>{$value['message']}</td></tr>";
			}
			$output.= "</table>\n";
			$output.= "</div>";
		}
		return $output;
	}

}

?>