<?php

namespace Staempfli\Voucher\Library;


class Cli
{
    /** @var App */
    var $app = null;
    var $commands = [];

    public function __construct($app)
    {
        $this->app = $app;
        $this->commands = $this->getAllCommands();
        $this->handleCliRequest();
    }
    
    private function getAllCommands(){
        $classes  = array();
        $declaredClasses = $this->declareCommandClasses();
        $declaredClasses = get_declared_classes();
        foreach($declaredClasses as $class){
            if(is_subclass_of($class, "Staempfli\Voucher\Library\Command")) {
                $classes[] = $class;
            }
        }
        $commands = [];
        foreach ($classes as $class){
            $command = new $class($this->app);
            $commands[$command->getName()] = $command;
        }
        return $commands;
    }

    private function handleCliRequest(){
        $argv = $_SERVER['argv'];
        if(isset($argv[1]) && isset($this->commands[$argv[1]])){
            $command = $this->commands[$argv[1]];
            unset($argv[0]);
            unset($argv[1]);
            $parameters = $this->getParameters($argv);
            $arguments = $this->getArguments($command, $argv);
            echo $command->call($arguments, $parameters);
        }
    }

    private function getParameters(&$argv){
        $parameters = [];
        foreach ($argv as $key => $parameter){
            if(substr($parameter, 0, 1) == '--'){
                $identifier = substr($parts[0], 2);
                $parameters[$identifier] = true;
                unset($argv[$key]);
            } else if(substr($parameter, 0, 1) == '-' &&
            count(explode("=",$parameter)) == 2){
                $parts = explode("=",$parameter);
                $identifier = substr($parts[0], 1);
                $value = $parts[1];
                $parameters[$identifier] = $value;
                unset($argv[$key]);
            }
        }
        return $parameters;
    }

    private function getArguments($command, &$argv){
        $arguments = [];
        $commandArguments = $command->getArguments();
        $argv = array_values($argv);
        foreach ($argv as $key => $parameter){
            if(isset($commandArguments[$key])){
                $arguments[$commandArguments[$key]] = $parameter;
            }
        }
        return $arguments;
    }

    private function declareCommandClasses(){
        $files = glob( realpath(dirname(__FILE__)). '/../Command/*.php');
        foreach ($files as $file){
            include_once $file;
        }
    }

}