<?php
/**
 * Logger
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Logger
{
    const ERROR = "error";
    const NOTICE = "notice";

    var $config = [];
    /** @var Helper */
    var $helper = null;

    public function __construct($config, $helper)
    {
        $this->config = $config;
        $this->helper = $helper;
    }

    public function log($message, $level = self::NOTICE){
        $message = date("Y-m-d H:i:s").": ".$message."\n";
        $file = $this->helper->getIndexPath()."var/log/".$level.".txt";
        file_put_contents($file, $message, FILE_APPEND);
    }
}