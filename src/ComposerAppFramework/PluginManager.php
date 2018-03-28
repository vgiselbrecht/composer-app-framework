<?php

namespace ComposerAppFramework;

use ComposerAppFramework\Singelton;

class PluginManager extends Singelton{

    var $plugins = [];

    public function addPlugin($plugin){
        if(is_subclass_of($plugin, "ComposerAppFramework\\Plugin")){
            $plugin->app = $this->app;
            $this->plugins[$plugin->getName()] = $plugin;
        }
    }

    public function addPlugins($plugins){
        foreach ($plugins as $plugin){
            $this->addPlugin($plugin);
        }
    }

    public function getPlugins(){
        return $this->plugins;
    }

    public function getPluginsForType($types){
        $retPlugins = [];
        if(!is_array($types)){
            $types = [$types];
        }
        foreach ($types as $type) {
            foreach ($this->plugins as $pluginname => $plugin) {
                if (in_array($type, $plugin->getType())) {
                    $retPlugins[$pluginname] = $plugin;
                }
            }
        }
        return $retPlugins;
    }

    public function callPluginFunction($functionname, &$data = [], $types = []){
        $plugins = [];
        $ret = "";
        if(!$types){
            $plugins = $this->getPlugins();
        }else{
            $plugins = $this->getPluginsForType($types);
        }
        foreach ($plugins as $plugin){
            if(method_exists($plugin, $functionname)){
                $ret .= $plugin->$functionname($data);
            }
        }
        return $ret;
    }

}