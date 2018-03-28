<?php
/**
 * Bootstra
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */
 
namespace ComposerAppFramework;

class Load
{
    /** @var App */
    var $app = null;

    public function __construct($config, $plugins = [], $min = false, $composer_app_path = null)
    {
        session_start();
        $this->app = new App($config, $composer_app_path);
        $this->loadRequest();
        $this->loadAssets();
        $this->loadDB();
        $this->app->translation = new Translation($this->app);
        $this->app->model = new Model($this->app);
        $this->app->plugin_manager = new PluginManager($this->app);
        $this->app->plugin_manager->addPlugins($plugins);
        if(!$min){
            $this->handleRequest();
        }
    }
    
    private function loadDB(){
        $db = new Database();
        $db->setDbConfig($this->app->getConfig());
        $this->app->setDb($db);
    }

    private function loadRequest(){
        $request = new Request();
        $request->initRequest($this->app->getConfig());
        $this->app->setRequest($request);
    }
    
    private function loadAssets(){
        $path = $this->app->getRequest()->getPath();
        if(isset($path[0]) && $path[0] == "assets"){
            $this->app->getAssets()->loadAsset();
            exit();
        }
    }

    private function handleRequest(){
        $routing = new Routing($this->app);
        $routing->initRequestRouting($this->app->getRequest());
        $this->app->setRouting($routing);
        $dispatcher = new Dispatcher($this->app);
        echo $dispatcher->handle($routing);
    }
}

?>