<?php
/**
 * Translation
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace Staempfli\Voucher\Library;


class Translation
{
    /** @var App */
    var $app = null;
    var $lang = 'en';
    var $translations = [];

    public function __construct($app)
    {
        $this->app = $app;
        $config = $this->app->getConfig();
        if(isset($config['lang'])){
            $this->lang = $config['lang'];
        }
    }

    public function getTranslation($args){
        $key = array_shift($args);
        if(!isset($this->translations[$this->lang])){
            $this->translations[$this->lang] = $this->loadTranslation($this->lang);
        }
        if(isset($this->translations[$this->lang][$key])){
            $value = $this->translations[$this->lang][$key];
            return vsprintf($value, $args);
        }
        return vsprintf($key, $args);
    }

    public function loadTranslation($lang){
        $ret = [];
        $file = realpath(dirname(__FILE__))."/../Resources/Private/i18n/".$lang.".csv";
        if(file_exists($file)){
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if(count($data) == 2){
                        $ret[$data[0]] = $data[1];
                    }
                }
                fclose($handle);
            }
        }
        return $ret;
    }

}