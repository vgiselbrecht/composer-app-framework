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
    
    public function loadAsset(){
        $path = $this->app->getRequest()->getPath();
        $file  = $this->app->getComposerAppPath()."/Resources/Public/".implode("/",$path);
        if(isset($path[0]) && $path[0] == "assets"){
            if(file_exists($file)){
                header($this->getContentType($file));
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
        }
        echo "Assets with path ".$file." not found!";
    }

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