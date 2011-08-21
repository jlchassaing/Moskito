<?php

/*!

\class lcModule
\version : 0.1

\brief This class manages the module execution
\author Jean-Luc Chassaing

*/

class lcModule
{

    private $view;

    private $module;

    private $viewList;

    private $conf;

    private $params;

    private $errorNumber;



    const DEFAULT_LAYOUT = "layout.tpl.php";

    /*!

    static module loader funciton
    \param string $module
    \param lcModule
    */
    public static function loadModule($module)
    {

        return new lcModule($module);

    }

    /*!

    class constructor
    \param string $module
    */
    public function __construct($module)
    {
        $this->setModule($module);
    }

    /*!

    Module setter this methode init the module loding its definition
    \param string $module
    */
    public function setModule($module)
    {
        $modulePath = "modules/$module";
        if (!file_exists($modulePath.'/module.php')){
            lcDebug::write('ERROR', "There is no module declaration file for the module : $module");
            return false;
        }
        else
        {
            $this->module = $module;
            include $modulePath.'/module.php';

            $this->viewList = $viewList;
            $this->conf = $moduleConf;
            return true;
        }

    }

    /*!

    Redirection method to an other module and view
    \param string $module
    \param string $view
    \param array $Params
    */
    public function redirectToModule($module,$view,$Params=null)
    {
        $redirectUrl = "/".$module."/".$view;
        foreach ($Params as $value)
        {
            $redirectUrl = $redirectUrl ."/".urlencode($value);

        }
        $url = lcHTTPTool::buildUrl($redirectUrl);
        lcHTTPTool::redirect($url);
    }

    /*!

    Set the default view for the module according to the module definition
    */
    public function setDefaultView()
    {
        $this->view = $this->conf['defaultView'];
    }

    /*!

    Check if the $module and $view function params are the current loaded
    module and view
    \param string $module
    \param string $view
    \param boolean
    */
    public function isCurrent($module,$view)
    {
        if ($this->module == $module and $this->view == $view)
        {
            return true;
        }
        else
        return false;
    }

    /*!

    Check if the loaded view name matches the $view
    \param string $view
    \param boolean
    */
    public function isCurrentView($view)
    {
        if ($this->view == $view)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!

    Check if an error has been set during exectuion
    \param boolean
    */
    public function isError()
    {
        if (isset($this->errorNumber) and $this->errorNumber > 0)
        {
            return true;
        }
        else
        {
            return  false;
        }
    }

    /*!

    Build the module execution result
    \param string
    */
    public function result()
    {

        $Result = $this->buildView();
        $tpl = new lcTemplate();

        if (isset($this->params['NodeId']))
        {
            $tpl->setVariable("currentNodeId", $this->params['NodeId']);
        }

        $tpl->setVariable("MainResult", $Result['content']);

        return $tpl->fetch($Result['layout']);
    }

    /*!

    Build the view result
    the view must return an array with a content key containing the template result.
    \param array
    */
    public function buildView()
    {
        $Result = array();
        $Result['content'] = "";
        $Result['layout'] = self::DEFAULT_LAYOUT;
        if (isset($this->viewList[$this->view]))
        {
            $pathToScript = "modules/".$this->module."/".$this->viewList[$this->view]['script'];
            $Params = $this->params;

            if (file_exists($pathToScript))
            {
                include $pathToScript;
            }
        }
        else
        {
            lcDebug::write("ERROR", "The view ".$this->view." does not exits for the current module.");
        }

        return $Result;
    }

    /*!

    Load a specific view for the current module
    returns true if the view has been correctly loaded
    \param string $view
    \param array $params
    \param boolean
    */
    public function loadView($view,$params = null)
    {
        if ($this->viewExists($view))
        {
            $this->view = $view;
            if (is_null($params))
            {
                $this->params = $this->loadParams();
                $this->params['Module'] = $this;
            }
            else
            {
                $this->params = $params;
                $this->params['Module'] = $this;
            }
            return true;

        }
        else
        {
            lcDebug::write('ERROR', "The requested view : $view does not exits in module : ".$this->module);
            return false;
        }

    }

    /*!

    Load the view params. This array will be passed to the module script
    \param mixed returns an array if the params were correctly loaded. Otherwise it will return a boolean false
    */
    public function loadParams()
    {
        $result = array();
        if (isset($this->viewList[$this->view]))
        {
            if (isset($this->viewList[$this->view]['ordered_params']))
            {
                $requestArray=$GLOBALS['RequestArray'];
                foreach ($this->viewList[$this->view]['ordered_params'] as $key=>$value)
                {
                    if (isset($requestArray[$key]))
                    {
                        $result[$value] = (is_numeric($requestArray[$key]))? (int)$requestArray[$key] :$requestArray[$key];
                    }

                }
            }
            return $result;
        }
        else
        {
            lcDebug::write("ERROR", "The view ".$this->view." does not exits for the current module.");
            return false;
        }

    }

    /*!

    Test if the specified view exists in the module viewList
    \param string $view
    */
    public function viewExists($view)
    {
        return isset($this->viewList[$view]);
    }

    /*!

    generic getter
    \param string $name
    \param mixed
    */
    public function __get($name)
    {
        return $this->$name;
    }

    /*!

    Generic setter
    \param string $name
    \param mixed $value
    */
    public function __set($name,$value)
    {
        $this->$name = $value;
    }

    /*!
     get the list of all defined modules
     \return array
     */
    public static function getModuleList()
    {
        $dirPath = "modules/";
        $moduleList = array();
        if (is_dir($dirPath))
        {
            if ($dh = opendir($dirPath))
            {
                while (($file = readdir($dh)) !== false)
                {
                    if (file_exists($dirPath.$file."/module.php"))
                    {
                        $moduleList[] = $file;
                    }
                }
                closedir($dh);
            }
        }
        return $moduleList;
    }

    /*!
     return the function list defined in the specified module definition
     \param string $moduleName
     \return array
     */
    public static function getModuleFunctions($moduleName)
    {
        $moduleDefPath = "modules/$moduleName/module.php";
        $functionList = array();
        include $moduleDefPath;

        return $functionList;

    }

    /*!
     check if $name matches the current view
     \param string $name
     */
    public function isCurrentAction($name)
    {
        if ($this->view == $name)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!
     return the current view function name
     \return string
     */
    public function functionName()
    {
        if(isset($this->viewList[$this->view]['function']))
            return $this->viewList[$this->view]['function'];
        else
            return false;
    }
}

?>