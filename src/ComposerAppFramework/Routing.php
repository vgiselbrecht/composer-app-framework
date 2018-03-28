<?php

namespace ComposerAppFramework;

class Routing{

    /** @var App */
    var $app = null;
    /** @var Request */
    var $request = null;
    var $controllerClass = "";
    var $actionName = "";
    var $arguments = [];
    var $pathInformation = [];
    var $fullPage = false;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function initRequestRouting($request){
        $this->request = $request;
        $this->fullPage = true;
        $this->loadRouting($this->request->getPath());
    }

    public function initInlineRouting($path){
        if(!is_array($path)){
            $path = explode("/",$path);
            foreach ($path as $key => $part){
                if(!$part){
                    unset($path[$key]);
                }
            }
            $path = array_values($path);
        }
        $this->loadRouting($path);
        if($this->getClassName() == "Error"){
            $this->app->getLogger()->log("The inline routing has an ".$this->getActionName()." error!");
            return false;
        }
        return true;
    }

    private function loadRouting($path){
        $this->loadPathInformation($path);
        if(!$this->pathInformation){
            $this->setPathInformation([],"Error", "notfound", []);
        }
        $this->controllerClass = $this->generateControllerClass();
        $this->actionName = $this->pathInformation['action'];
        $this->arguments = $this->pathInformation['arguments'];
    }

    public function getClassName(){
        return $this->controllerClass;
    }

    public function getActionName(){
        return $this->actionName;
    }

    public function getArguments(){
        return $this->arguments;
    }
    
    private function loadPathInformation($path){
        if(!$path){
            return $this->setPathInformation([], "Index", "index", []);
        }
        $directory = $this->app->getComposerAppPath()."/Controller/";
        $structur = [];
        foreach ($path as $key => $folder){
            unset($path[$key]);
            if(is_dir($directory.$folder)){
                $directory .= $folder."/";
            }else{
                $controllerFile = "";
                if(isset($folder)){
                    if(file_exists($directory.$folder."Controller.php")){
                        $arguments = [];
                        $action = "index";
                        if(isset($path[$key + 1])){
                            $action = $path[$key + 1];
                            $arguments = $path;
                            unset($arguments[$key + 1]);
                        }
                        $arguments = array_values($arguments);
                        return $this->setPathInformation($structur, $folder, $action, $arguments);
                    }
                }
                if(file_exists($directory."IndexController.php")){
                    return $this->setPathInformation($structur, "Index", "index", $path);
                }
            }
            $structur[] = $folder;
        }
        return false;
    }

    private function setPathInformation($structur, $file, $action, $arguments){
        $this->pathInformation['structur'] = $structur;
        $this->pathInformation['file'] = $file;
        $this->pathInformation['action'] = $action;
        $argumentsKeyValue = [];
        $arguments = array_values($arguments);
        for($i = 0; $i < count($arguments); $i=$i+2){
            if(isset($arguments[$i+1])){
                $argumentsKeyValue[$arguments[$i]] = urldecode($arguments[$i+1]);
            }else{
                $argumentsKeyValue[$arguments[$i]] = null;
            }
        }
        $this->pathInformation['arguments'] = $argumentsKeyValue;
    }

    private function generateControllerClass(){
        $class = "Staempfli\\Voucher\\Controller\\";
        $structur = $this->pathInformation['structur'];
        foreach ($structur as $level){
            $class .= $level."\\";
        }
        $class .= $this->pathInformation['file'];
        return $class;
    }

    /**
     * @return bool
     */
    public function isFullPage(): bool
    {
        return $this->fullPage;
    }
}