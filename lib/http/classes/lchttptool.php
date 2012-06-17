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

    private $getValues;
    
    private $requestArray;

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
        $this->getValues = array();
        
        $scriptName = $_SERVER["SCRIPT_NAME"];
        $uriRoot = $scriptName;
        if (strpos($scriptName,"index.php"))
        {
            $uriRoot = str_replace("index.php", "", $scriptName);
        }
        
        
        $this->requestArray = array(
                "host"         => $_SERVER["HTTP_HOST"],
                "documentRoot" => $_SERVER["DOCUMENT_ROOT"],
                "scriptName"   => $scriptName,
                "pathInfo"     => isset($_SERVER["PATH_INFO"])?$_SERVER["PATH_INFO"]:null,
                "queryString"  => $_SERVER["QUERY_STRING"],
                "requestUri"   => isset($_SERVER["REQUEST_URI"])?$_SERVER["REQUEST_URI"]:null,
                "uriRoot"      => $uriRoot
        );
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

       /* $result['host'] = $_SERVER['HTTP_HOST'];
        $scriptName = substr($_SERVER['SCRIPT_NAME'],1);
        $scriptNameArray = explode("/",$scriptName);
        $request = $_SERVER['REQUEST_URI'];
        if ($_SERVER['QUERY_STRING'] != "")
        {
            $len = strlen($_SERVER['QUERY_STRING']) *-1;
            $request = substr($request,0, $len);
            $request = trim($request);
            $request = trim(str_replace("/?", "/", $request));
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
        //$result['fullrequest'] = str_replace("?", "", $result['fullrequest']);
        $result['url_alias'] = (substr($request, -1) == "/")?substr($request,0 -1):$request;
        //$result['url_alias'] = str_replace("?", "", $result['url_alias']);
        $getParaPos = strpos($request, "/(");
        if ($getParaPos > 0)
        {
            $result['url_alias'] = substr($result['url_alias'], 0,$getParaPos);
        }


        if ($request != "/")
        {
            $request = substr($request, 1);
            preg_match("#\((\w+)\)/(\w+)#", $request,$getValues);

                if (count($getValues) > 2)
                {
                    for($i =1 ; $i  < count($getValues) ; $i=$i+2)
                    {
                        $this->getValues[$getValues[$i]] = $getValues[$i+1];
                    }
                }


            $result['request'] = explode("/",$request);
        }
        else
        {
            $result['request'] = null;
        }
        $this->request = $result;

        return $result;
        */
        $result['host'] = $this->requestArray['host'];
        $scriptCall = $this->requestArray['scriptName'];
        $requestUri = $this->requestArray['requestUri'];
        $queryString = $this->requestArray['queryString'];
        
        $substrBegin=1;
        if (!strpos($requestUri,"index.php"))
        {
            $scriptCall = str_replace("index.php", "", $scriptCall);
            $substrBegin = 0;
        }
        $substrBegin = $substrBegin + strlen($scriptCall);
        $requestUriLenght = strlen($requestUri);
        $substrEnd = $requestUriLenght - $substrBegin;
        if ($queryString !== "")
        {
            $queryLength = strlen($queryString);
            $substrEnd =  -$queryLength;
        
        }
        $requestString = substr($requestUri, $substrBegin,$substrEnd);
        
        if ($requestString !== "" and is_string($requestString))
        {
            $result['request'] = explode("/", $requestString);
        }
        else
        {
            $result['request'] = array();
        }
        
        $result['fullrequest'] = (substr($requestString, -1) != "/")?$requestString."/":$requestString;
        $result['url_alias'] = (substr($requestString, -1) == "/")?substr($requestString,0 -1):$requestString;
        
        $this->request = $result;
        return $result;

    }

    public function getRefferer()
    {
        $refferer = $_SERVER['HTTP_REFERER'];
    }

    public function makeUrl($path,$full=false)
    {
        return self::buildUrl($path,false,$full);
    }

    public static function buildUrl($path,$toFile=false,$full=false)
    {
        $http = lcHTTPTool::getInstance();
        $accessLoader = lcAccess::getInstance();
        if (substr($path,0,1) != "/")
        {
            $path = "/".$path;
        }
        $pre = "/";
        $access = "";

        if ($accessLoader->accessType == lcAccess::URI and !$toFile)
        {

             $access = $pre.$accessLoader->currentAccess;
        }

                $uri = $http->fromRoot($access.$path);
                if ($full)
                {
                    $uri = "http://".$GLOBALS['siteHost'].$uri;
                }

        return $uri;

    }
    
    public function fromRoot($path)
    {
        $newPath = $this->requestArray['uriRoot'].$path;
        $newPath = str_replace("//", "/", $newPath);
        return $newPath;
    }

/*
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
*/

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
        else if (isset($_GET[$name]) or isset($this->getValues[$name]))
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

    public function hasGetVariable($name)
    {
        return isset($_Get[$name]) or isset($this->getValues[$name]);
    }

    public function getVariable($name)
    {
        if (isset($_GET[$name]))
        {
             return $this->returnValue($_GET, $name);
        }
        elseif (isset($this->getValues[$name]))
        {
             return $this->returnValue($this->getValues, $name);
        }

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
       if ($uri != "/" and substr($uri,-1) == "/")
        {
            $uri = substr($uri, 0, -1);
        }
        header("Location:".$uri);
    }
    
    public static function get($url)
    {
        
        /*Initialisation de la ressource curl*/
        $c = curl_init();
        /*On indique à curl quelle url on souhaite télécharger*/
        curl_setopt($c, CURLOPT_URL, $url);
        /*On indique à curl de nous retourner le contenu de la requête plutôt que de l'afficher*/
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        /*On indique à curl de ne pas retourner les headers http de la réponse dans la chaine de retour*/
        curl_setopt($c, CURLOPT_HEADER, false);
        /*On execute la requete*/
        $output = curl_exec($c);
        /*On a une erreur alors on la lève*/
        if($output === false)
        {
            trigger_error('Erreur curl : '.curl_error($c),E_USER_WARNING);
        }
        /*Si tout c'est bien passé on affiche le contenu de la requête*/
        else
        {
            return $output;
        }
        /*On ferme la ressource*/
        curl_close($c);
        
    }
}



?>