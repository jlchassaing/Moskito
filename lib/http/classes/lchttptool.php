<?php



/*!

 	\class lcHTTPTool lchttptool.php
 	\version 1.0
	Http tool class gives access to http datas
	builds url and performs http requests
	\author jlchassaing

*/
class lcHTTPTool
{

    /*!
     *
    Singleton
    \var lcHTTPTool
    */
    private static $instance;

    /*!
     *
    request data array
    $request['host'] is the host name
    $request['request'] holds and array of the
    access, module and view
    *
    \var array
    */
    private $request;


    /*!
     *
    Singleton function return the lcHTTPTool instance
    \return lcHTTPTool
    */
    public static function getInstance()
    {
        if (! self::$instance instanceOf lcHTTPTool)
        {
            self::$instance = new lcHTTPTool();
        }
        return self::$instance;
    }

    /*!
     *
    constructor
    */
    private function __construct()
    {

    }

    public function httpRequest()
    {
        return false;
    }

    /*!
     *
    Displays all $_SERVER variables
    */
    public function displayHeaders()
    {
        foreach ($_SERVER as $key=>$value) {
            echo "\$_SERVER[$key]=$value </br >";
        }
    }

    /*!
     *
    anlyses the request to extract the host and request
    parameters to build to preform the result
    */
    public function getRequest()
    {
        $result= array();

        // TODO : rewrite this part

        $result['host'] = $_SERVER['HTTP_HOST'];
        $scriptName = substr($_SERVER['SCRIPT_NAME'],1);
        $scriptNameArray = explode("/",$scriptName);
        $request = $_SERVER['REQUEST_URI'];
        if ($_SERVER['QUERY_STRING'] != "")
        {
            $len = strlen($_SERVER['QUERY_STRING']) *-1;
            $request = substr($request,0, $len);
            $request = trim(str_replace("?", "", $request));
        }

        if (substr($request,1) == $scriptName)
        {
            $request = "/";
        }
        else
        {
            $pos = false;
            $i = 0;
            while(($pos = stripos($request,$scriptNameArray[$i])) !== false)
            {
                $request = substr($request, $pos + strlen($scriptNameArray[$i]));

                $i++;
                if (!isset($scriptNameArray[$i]))
                {
                    break;
                }
            }
        }



        $result['fullrequest'] = (substr($request, -1) != "/")?$request."/":$request;
        $result['fullrequest'] = str_replace("?", "", $result['fullrequest']);
        $result['url_alias'] = (substr($request, -1) == "/")?substr($request,0 -1):$request;
        $result['url_alias'] = str_replace("?", "", $result['url_alias']);
        if ($request != "/")
        {
            $request = substr($request, 1);
            $result['request'] = explode("/",$request);
        }
        else
        {
            $result['request'] = null;
        }

        return $result;

    }

    public function makeUrl($path)
    {
        return self::buildUrl($path);
    }


    public static function buildUrl($path,$toFile=false,$full=false)
    {
        $access = "";
        $accessLoader = lcAccess::getInstance();
        $isAccess = false;
        $settings = lcSettings::getInstance();
        $defaultAccess = $settings->value('Default','access');
        $aPath = explode("/", $path);
        if (count($aPath) > 1 )
        {
            if ($aPath[0] == "" and $accessLoader->isAccess($aPath[1]))
            {
                $isAccess = true;
            }
        }
        else
        {
            if ($accessLoader->isAccess($aPath[0]))
            {
                $isAccess = true;
            }
        }
        if (!$isAccess)
        {
            if ($path == "/")
            {
                $access = "";
            }
            else
            {
                if (isset($GLOBALS['SETTINGS']['currentAccess']))
                {
                    if ($defaultAccess != $GLOBALS['SETTINGS']['currentAccess'])
                    {
                        $access = '/'. $GLOBALS['SETTINGS']['currentAccess'];
                    }


                }
            }
            if (substr($path,0,1) != '/')
            {
                $path = '/'.$path;
            }
        }

        $host = "";
        if (isset($GLOBALS['SETTINGS']['sitehost']))
        {
            $host = $GLOBALS['SETTINGS']['sitehost'];
        }

        $script = "";


        $script = $_SERVER['SCRIPT_NAME'];
        if (isset($_SERVER['REDIRECT_URL']))
        {
            $script = substr($script, 0,-9);
        }

        if ($toFile)
        {
            $fullUrl = $script.$path;
            $fullUrl = str_replace("index.php", "", $fullUrl);
        }
        else
        {
            //$fullUrl = $host.$script.$access.$path;
            $fullUrl = $script.$access.$path;
        }

        $fullUrl = str_replace("//", "/", $fullUrl);
        if ($full)
        {
            $fullUrl = "http://".$host.$fullUrl;
        }
        return $fullUrl;
    }


    public static function fileUrl($path)
    {
        return self::buildUrl($path,true);
    }

    public function hasSessionVariable($name)
    {
        return isset($_SESSION[$name]);
    }

    public function sessionVariable($name)
    {
        return $this->returnValue($_SESSION, $name);
    }

    public function hasVariable($name)
    {
        if (isset($_POST[$name]))
        return true;
        else if (isset($_GET[$name]))
        return true;
        else if (isset($_SESSION[$name]))
        return true;
        else
        return false;
    }


    public function hasPostVariable($name)
    {
        return isset($_POST[$name]);
    }

    public function postVariable($name)
    {
        return $this->returnValue($_POST, $name);
    }

    private function returnValue($array,$name)
    {
        if (is_numeric($array[$name]))
        {
            return (int) $array[$name];
        }
        else
        {
            return $array[$name];
        }
    }

    public static function redirect($uri)
    {
        // header( $_SERVER['SERVER_PROTOCOL'] .  " 307" );
        // header( "Status", "307" );
        if (substr($uri,-1) == "/")
        {
            $uri = substr($uri, 0, -1);
        }
        header("Location:".$uri);
    }
}



?>