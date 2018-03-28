<?php

namespace ComposerAppFramework;

class Helper{

    public function getIndexPath(){
        return  dirname($_SERVER['SCRIPT_FILENAME']) . '/';
    }
}