<?php

/*!
 \class lcTemplate lctemplate.php
\version 0.1
\author jean-luc Chassaing

mange template loading and execution
*/

class lcTemplate
{
    private $params;
    private $templateData;


    public function __construct()
    {
        $this->params = array();
        $this->templateData = array();
    }

    /*!
     *
    build path to the template file based on the defined design or the standard desing.
    \param string $tplName
    \return mixed <boolean, string>
    */
    public function buildTemplatePath($tplName)
    {
        $tplPath = "/templates/".$tplName;

        return $this->loadDesign($tplPath);
    }


    function get_include_contents($filename)
    {
        if (is_file($filename)) {
            ob_start();
            if (is_array($this->params))
            {

                extract($this->params);
            }

            include $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }

    public function templateRun($templateFile,$params)
    {
        if (is_file($templateFile)) {
            ob_start();
            if (is_array($params))
            {
                extract($params);
            }

            include $templateFile;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }

    /*!
     load a design configuration
    \param $elt string
    \param $doUrl boolean
    */
    public function loadDesign($elt,$doUrl=false)
    {
        $path= false;
        $designRoot = "design/";
        if (substr($elt, 0,1) != "/")
        {
            $elt = "/".$elt;
        }

        if (isset($GLOBALS['settings']['currentDesign']))
        {
            $designPath = $GLOBALS['settings']['currentDesign'];
        }
        if (!file_exists($designRoot.$designPath.$elt))
        {
            $designPath = "standard";
            if (file_exists($designRoot.$designPath.$elt))
            {
                $path = $designRoot.$designPath.$elt;
            }
        }
        else {
            $path = $designRoot.$designPath.$elt;
        }
        if ($doUrl)
        {
            $path = "/".$path;
        }

        lcDebug::write('NOTICE', "found design path : $path in Design");
        return $path;
    }

    /*!
     fetch a template file return false if the tempalte can't be found
    \param string
    */
    public function fetch($templateName)
    {
        $result = false;
        $tplPath = $this->buildTemplatePath($templateName);

        lcDebug::write('NOTICE', "loading template : $tplPath ");
        if (file_exists($tplPath))
        {

            $result = $this->get_include_contents($tplPath);

        }
        return $result;
    }

    /*!
     set a template variable. The variable identified by $name will be
    available in the template
    \param string $name
    \param string $value
    */
    public function setVariable($name,$value)
    {
        $this->params[$name] = $value;
    }

    /*!
     return a template variable. if the variable is not set then return null
     \param string $name variable name to retrieve
     \return mixed
     */
    public function variable($name)
    {
        if (isset($this->params[$name]))
        {
            return $this->params[$name];
        }
        else
        {
            return null;
        }
    }

    /*
     * retreive the templateData array
     */
    public function getTemplateData()
    {
        return $this->templateData;
    }

    /*!
     deals with the methods that are called in the templates
    \param string $methode, the name of the method to execute
    \param array $params the param array
    \return mixed
    */
    public function __call($methode,$params)
    {
        switch ($methode)
        {
            case "url":
                $http = lcHTTPTool::getInstance();
                $full = isset($params[2])?$params[2]:false;

                $url = $http->makeUrl($params[0],$full);
                if (isset($params[1]) AND  $params[1] == 'no')
                    return $url;
                else
                    echo $url;
                break;
            case "attributeView":
                $tplSrc = $params[0];
                if (isset($params[1]))
                {
                    $data = $params[1];
                    $dataType = "";
                    if ($data instanceof lcContentObjectAttribute)
                    {
                        $dataType = $data->attribute('datatype');
                    }
                    else if(is_array($data) and isset($data['DataType']))
                    {
                        $dataType = $data['DataType'];
                    }
                    if ($dataType != "")
                    {
                        $tplSrc = $tplSrc."/".$dataType.".tpl.php";
                    }
                }

                $tpl = $this->buildTemplatePath($tplSrc);
                lcDebug::write('NOTICE', "loading template : $tpl ");
                if ($tpl)
                {
                    include $tpl;
                }
                else
                {
                    return false;
                }


                break;

            case "includeTpl":
                $tplSrc = $params[0];
                $paramList = (isset($params[1]))?$params[1]:null;

                $tpl = $this->buildTemplatePath($tplSrc);
                lcDebug::write('NOTICE', "loading template : $tpl ");
                if ($tpl)
                {
                    $cache = lcCache::getInstance();
                    $params = array('tpl'=> $tpl,
                                    'paramList' => $paramList);
                    if (($content = $cache->hasValidCacheFile($params)) == false)
                    {
                        $content =  $this->templateRun($tpl, $paramList);
                        $cache->makeCacheFile($params, $content);
                    }
                    echo $content;

                }
                else
                {
                    return false;
                }


                break;

            case "designurl":

                echo lcDesign::designUrl($params[0]);


                break;
            case "require_script":

                if (!isset($this->templateData['required_scripts']))
                {
                    $this->templateData['required_scripts'] = array();
                }
                $this->templateData['required_scripts'] = array_merge($this->templateData['required_scripts'],$params[0]);
                $scriptLoader = lcScriptLoader::getInstance();
                $scriptLoader->required($params[0]);
                break;
            case "require_style":

                if (!isset($this->templateData['required_styles']))
                {
                    $this->templateData['required_styles'] = array();
                }
                $this->templateData['required_styles'] = array_merge($this->templateData['required_styles'],$params[0]);
                $styleLoader = lcStyleLoader::getInstance();

                $styleLoader->required($params[0]);

                break;
            case "view":

                break;
            case "moduleView":
                    $ModuleName = $params[0];
                    $ModuleView = $params[1];
                    $ModuleParams = array('View' =>$params[2]);
                    $ModuleParams['Node'] = $params[3];

                    $module = lcModule::loadModule($ModuleName);
                    $module->loadView($ModuleView,$ModuleParams);
                    $result = $module->buildView();
                    echo $result['content'];
                 break;
            case "rest":
                $url = $params[0];
                $operation = $params[1];
                $request = $params[2];
                    $result = lcHTTPTool::get($url.'rest/'.$operation.'/'.$request);
                    
                    return json_decode($result);
                break;
            default:
                echo"call of : $methode ";
            break;
        }

    }

}

?>