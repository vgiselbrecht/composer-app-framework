<?php
/**
 * Block
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Block
{

    /** @var App */
    var $app = null;
    var $values = [];
    var $file = [];

    public function __construct($app, $file, $values = [])
    {
        $this->app = $app;
        $this->values = $values;
        $this->file = $file;
    }

    /**
     * return a value from controller
     * @param $key
     * @param null $subkey get a value from array with this key
     * @return mixed|null
     */
    public function receive($key, $subkey = null){
        if(!$subkey) {
            if (isset($this->values[$key])) {
                return $this->values[$key];
            }
        }else{

        }if (isset($this->values[$key][$subkey])) {
            return $this->values[$key][$subkey];
        }
        return null;
    }

    /**
     * include template file
     * @return string
     */
    public function includeTemplate(){
        ob_start();
        include $this->file;
        return ob_get_clean();
    }

    /**
     * add partial
     * @param $partial path of partial
     * @param array $values asset values
     * @return string
     */
    public function loadPartial($partial, $values = []){
        return $this->app->getTemplate()->renderPartials($partial, $values);
    }

    /**
     * load a controller and set return controller content
     * @param $path
     * @return bool|string
     */
    public function loadController($path){
        $routing = new Routing($this->app);
        if($routing->initInlineRouting($path)){
            $dispatcher = new Dispatcher($this->app);
            return $dispatcher->handle($routing);
        }else{
            $this->app->getLogger()->log("Controller with path ".$path." could not be loaded!", $this->app->getLogger()::ERROR);
        }
    }

    public function getApp(){
        return $this->app;
    }

    /**
     * translate by key
     * @return string
     */
    public function __(){
        $args = func_get_args();
        return $this->app->getTranslation()->getTranslation($args);
    }

}