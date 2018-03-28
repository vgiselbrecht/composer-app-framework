<?php
/**
 * Request
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Request
{
    var $get = [];
    var $post = [];
    var $path = [];

    public function __construct()
    {
        return $this;
    }

    public function initRequest($config)
    {
        $this->setPost($_POST);
        $this->setGet($_GET);
        $this->handlePath($config);
    }

    public function getParameters(){
        return $this->getGet() + $this->getPost();
    }

    public function getParameter($key){
        $parameters = $this->getParameters();
        if(isset($parameters[$key])){
            return $parameters[$key];
        }
        return null;
    }

    
    private function handlePath($config){
        if(!isset($_SERVER["REQUEST_URI"])){
            return false;
        }
        $url =  strtok($_SERVER["REQUEST_URI"],'?');
        $parts = explode("/", $url);
        foreach ($parts as $key => $part){
            if(!$part){
                unset($parts[$key]);
            }
        }
        $parts = array_values($parts);
        if(isset($config['root'])){
            $rootparts = explode("/", $config['root']);
            foreach ($parts as $key => $part){
                if(isset($rootparts[$key])){
                    if($rootparts[$key] = $part){
                        unset($parts[$key]);
                        continue;
                    }
                }
                break;
            }
        }
        $parts = array_values($parts);
        $this->setPath($parts);
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @param array $get
     */
    public function setGet(array $get)
    {
        if(!is_array($get)){
            $get = [];
        }
        $this->get = $get;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @param array $post
     */
    public function setPost(array $post)
    {
        if(!is_array($post)){
            $post = [];
        }
        $this->post = $post;
    }

    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }

    /**
     * @param array $path
     */
    public function setPath(array $path)
    {
        $this->path = $path;
    }
}