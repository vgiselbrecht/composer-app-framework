<?php
/**
 * Command
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Command
{
    /** @var App */
    var $app = null;
    var $name = "";
    var $description = "";
    var $arguments = [];

    public function __construct($app)
    {
        $this->app = $app;
        $this->configure();
    }

    public function call($arguments, $parameters){
        return $this->execute($arguments, $parameters);
    }


    protected function configure()
    {
        $this->setName(get_class($this));
        $this->setDescription("");
    }

    protected function execute($arguments, $parameters)
    {

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setArgument($name){
        $this->arguments[] = $name;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * return info about the comment
     * @param bool $full if true than it return the call stack
     * @return string
     */
    public function getInfo($full = true){
        $ret = $this->getName()." - ".$this->getDescription()."\n";
        if($full) {
            $ret .= "Call: " . $this->getName() . " " . implode(" ", $this->getArguments()) . "\n";
        }
        return $ret;

    }

}