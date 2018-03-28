<?php

namespace ComposerAppFramework;


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

    /**
     * return list of all comments
     * @return array
     */
    private function getAllCommands(){
        $classes  = array();
        $declaredClasses = $this->declareCommandClasses();
        $declaredClasses = get_declared_classes();
        foreach($declaredClasses as $class){
            if(is_subclass_of($class, "ComposerAppFramework\Command")) {
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

    /**
     * handle a clie request
     */
    private function handleCliRequest(){
        $argv = $_SERVER['argv'];
        if(isset($argv[1]) && isset($this->commands[$argv[1]])){
            $command = $this->commands[$argv[1]];
            unset($argv[0]);
            unset($argv[1]);
            $parameters = $this->getParameters($argv);
            $arguments = $this->getArguments($command, $argv);
            if($this->validArguments($command, $arguments)){
                echo $command->call($arguments, $parameters);
            }
        }else if(!isset($argv[1]) ||  $argv[1] == 'list:'){
            echo $this->getList();
        }else{
            echo 'Command not found, call "list" to show all functions!';
        }
    }

    private function getList(){
        $ret = "";
        $ret .= "Command list\n";
        $ret .= "---------------------------------------\n";
        foreach ($this->commands as $command){
            $ret .= $command->getInfo(false);
        }
        return $ret;
    }

    /**
     * get cli paremeters
     * @param $argv
     * @return array
     */
    private function getParameters(&$argv){
        $parameters = [];
        foreach ($argv as $key => $parameter){
            if(substr($parameter, 0, 2) == '--'){
                $identifier = substr($parameter, 2);
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

    /**
     * get cli parameters
     * @param $command
     * @param $argv
     * @return array
     */
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

    /**
     * valid arguments
     * @param $command
     * @param $arguments
     */
    private function validArguments($command, $arguments){
        $commandArguments = $command->getArguments();
        if(count($arguments) != count($commandArguments)){
            echo "Missing arguments\n";
            echo $command->getInfo();
            return false;
        }

        return true;
    }

    /**
     * include all cli classes
     */
    private function declareCommandClasses(){
        $files = glob( $this->app->getComposerAppPath(). '/Command/*.php');
        foreach ($files as $file){
            include_once $file;
        }
    }

}