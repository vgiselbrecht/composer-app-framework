<?php

namespace Staempfli\Voucher\Library;

class Helper{

    public function getIndexPath(){
        return  dirname($_SERVER['SCRIPT_FILENAME']) . '/';
    }
}