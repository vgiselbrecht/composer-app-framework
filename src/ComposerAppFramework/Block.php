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

    public function includeTemplate(){
        ob_start();
        include $this->file;
        return ob_get_clean();
    }

    public function loadPartial($partial, $values = []){
        return $this->app->getTemplate()->renderPartials($partial, $values);
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

    public function getApp(){
        return $this->app;
    }

    public function __(){
        $args = func_get_args();
        return $this->app->getTranslation()->getTranslation($args);
    }

}