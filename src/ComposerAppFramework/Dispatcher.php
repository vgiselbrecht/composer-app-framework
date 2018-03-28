<?php
/**
 * Dispatcher
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Dispatcher
{
    /** @var App */
    var $app = null;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function handle(Routing $routing){
        $classname = $routing->getClassName();
        $classname .= "Controller";
        $actionname = $routing->getActionName();
        $actionname .= "Action";
        if(!class_exists($classname)){
            $this->app->getLogger()->log("Controller with Name $classname does not exist", $this->app->getLogger()::ERROR);
            return false;
        }
        $controller = new $classname($this->app, $routing->getArguments());
        if(!is_subclass_of($controller, "ComposerAppFramework\\Controller")){
            $this->app->getLogger()->log("Controller with Name $classname must have controller as parent", $this->app->getLogger()::ERROR);
            return false;
        }
        if(!method_exists($controller, $actionname)){
            $this->app->getLogger()->log("Action with Name $actionname not exists in $classname", $this->app->getLogger()::ERROR);
            return false;
        }
        if($routing->isFullPage() && !$controller->notFullPage()){
            $controller->setFullPage();
            return $this->app->getTemplate()->renderFullPage($controller, $routing);
        }else{
            return $this->app->getTemplate()->renderController($controller, $routing);
        }
    }

}