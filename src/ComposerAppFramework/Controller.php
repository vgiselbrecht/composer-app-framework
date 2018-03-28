<?php
/**
 * Controller
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Controller
{
    /** @var App */
    var $app = null;
    var $arguments = null;
    var $values = null;
    var $pagetitle = "";
    var $fullPage = false;
    protected $notFullPage = false;

    public function __construct($app, $arguments)
    {
        $this->app = $app;
        $this->arguments = $arguments;
        $this->init();
    }

    public function setFullPage(){
        $this->fullPage = true;
        $this->app->getEvents()->registerListener("change_page_title", array($this, 'getPageTitle'));
    }

    protected function init(){

    }

    public function getArguments(){
        return $this->arguments;
    }

    public function getArgument($key){
        if(isset($this->arguments[$key])){
            return $this->arguments[$key];
        }
        return null;
    }

    public function assign($key, $value){
        $this->values[$key] = $value;
    }

    public function getValues(){
        return $this->values;
    }

    public function getPageTitle(&$pagetitle){
        if($this->pagetitle){
            $pagetitle = $this->pagetitle." - ".$pagetitle;
        }
    }

    public function redirect($path){
        $root = "";
        if(isset($this->app->getConfig()['root'])){
            $root =  "/".$this->app->getConfig()['root'];
        }
        header('Location: '.$root.$path);
        exit();
    }

    public function setPageTitle($pagetitle){
        $this->pagetitle = $pagetitle;
    }
    
    public function loadPageTitle(){
        $pagetitle = "";
        $config = $this->app->getConfig();
        if($config['pagetitle']){
            $pagetitle =  $config['pagetitle'];
        }
        if(!$pagetitle){
            $pagetitle = "Stämpfli Voucher System";
        }
        $this->app->getEvents()->triggerEvent("change_page_title", $pagetitle);
        return $pagetitle;
    }

    public function __(){
        $args = func_get_args();
        return $this->app->getTranslation()->getTranslation($args);
    }

    public function loadController($path){
        $routing = new Routing($this->app);
        if($routing->initInlineRouting($path)){
            $dispatcher = new Dispatcher($this->app);
            return $dispatcher->handle($routing);
        }else{
            $this->app->getLogger()->log("Controller with path ".$path." could not be loaded!", $this->app->getLogger()::ERROR);
        }
    }

    public function addSuccess($msg){
        $this->app->getSession()->setAlert($msg,$this->app->getSession()::SUCCESS);
    }

    public function addError($msg){
        $this->app->getSession()->setAlert($msg,$this->app->getSession()::ERROR);
    }

    public function addNotice($msg){
        $this->app->getSession()->setAlert($msg,$this->app->getSession()::NOTICE);
    }

    public function getPostValue($key){
        $post = $this->app->getRequest()->getPost();
        if(isset($post[$key])){
            return $post[$key];
        }
    }

    public function notFullPage(){
        return $this->notFullPage;
    }

    public function setHeader($header){
        header($header);
    }

}