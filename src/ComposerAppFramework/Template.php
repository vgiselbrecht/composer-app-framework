<?php
/**
 * Template
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Template
{
    /** @var App */
    var $app = null;
    var $requestAction = "";

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * render a full page template
     * @param $controller
     * @param $routing
     * @return string
     */
    public function renderFullPage($controller, $routing){
        if($controller && $routing){
            $this->requestAction = $this->loadAction($controller, $routing);
        }
        $content = $this->renderPartials("index");
        return $content;
    }

    /**
     * render a in page controller
     * @param $controller
     * @param $routing
     * @return string
     */
    public function renderController($controller, $routing){
        $content =  $this->loadAction($controller, $routing);
        return $content;
    }

    /**
     * get the content of the full page controller
     * @return string
     */
    public function loadRequestAction(){
        return $this->requestAction;
    }

    /**
     * call a action and return the action content
     * @param $controller
     * @param $routing
     * @return string
     */
    private function loadAction($controller, $routing){
        $actionname = $routing->getActionName();
        $actionname .= "Action";
        $actionContent = $controller->$actionname();
        if($actionContent !== null){
            return $actionContent;
        }
        return $this->renderTemplate($controller, $routing);
    }

    /**
     * render a template file from a action
     * @param $controller
     * @param $routing
     * @return string
     */
    public function renderTemplate($controller, $routing){
        $directory = $this->getTemplatePath();
        $classname = $routing->getClassName();
        $path = str_replace("\\","/",substr($classname, 29));
        $file = $directory.$path."/".$routing->getActionName().".phtml";
        if(file_exists($file)){
            $block = new Block($this->app, $file, $controller->getValues());
            return $block->includeTemplate();
        }else{
            $this->app->getLogger()->log("Template File $file not exists!", $this->app->getLogger()::ERROR);
        }
    }

    /**
     * render a partial file
     * @param $file
     * @param array $values
     * @return string
     */
    public function renderPartials($file, $values = []){
        $file =  $directory = $this->getPartialPath().$file.".phtml";
        if(file_exists($file)){
            $block = new Block($this->app, $file, $values);
            return $block->includeTemplate();
        }else{
            $this->app->getLogger()->log("Partial File $file not exists!", $this->app->getLogger()::ERROR);
        }
    }

    public function getTemplatePath(){
        return $this->app->getComposerAppPath()."/Resources/Private/Template/";
    }

    public function getPartialPath(){
        return $this->app->getComposerAppPath()."/Resources/Private/Partials/";
    }

}