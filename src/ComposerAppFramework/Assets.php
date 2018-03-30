<?php
/**
 * Assets
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Assets
{

    var $app = null;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * load asset for request
     */
    public function loadAsset(){
        $path = $this->app->getRequest()->getPath();
        $file  = $this->app->getComposerAppPath()."/Resources/Public/".implode("/",$path);
        if(isset($path[0]) && $path[0] == "assets"){
            if(file_exists($file)){
                header($this->getContentType($file));
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header("Expires: " . date("D, j M Y", strtotime("tomorrow")) . " 02:00:00 GMT");
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
        }
        header("HTTP/1.0 404 Not Found");
        echo "Assets with path ".$file." not found!";
        exit();
    }

    /**
     * get mime type for given file
     * @param $file path to file
     * @return string
     */
    public function getContentType($file){
        $parts = explode(".",$file);
        $filetype = $parts[count($parts) - 1];
        $mimmtype = "application/octet-stream";
        switch ($filetype){
            case 'css':
                $mimmtype = "text/css";
                break;
            case 'js':
                $mimmtype = "text/javascript";
                break;
            default:
                $mimmtype = mime_content_type($file);
        }
        return "Content-Type: ".$mimmtype;
    }

}