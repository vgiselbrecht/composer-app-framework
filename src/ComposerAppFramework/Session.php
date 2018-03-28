<?php
/**
 * Session
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace Staempfli\Voucher\Library;


class Session
{

    const SUCCESS = 'success';
    const NOTICE = 'notice';
    const ERROR = 'error';

    /** @var App */
    var $app = null;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function setSessionData($key, $value){
        $_SESSION['voucher'][$key] = $value;
    }

    public function getSessionData($key){
        if(isset($_SESSION['voucher'][$key] )){
            return $_SESSION['voucher'][$key];
        }
    }

    public function setUserId($userId){
        $this->setSessionData("user_id", $userId);
    }

    public function getUserId(){
        return $this->getSessionData("user_id");
    }

    public function setAlert($msg, $type){
        $alerts = $this->getSessionData('alerts');
        $alerts[$type][] = $msg;
        $this->setSessionData('alerts', $alerts);
    }

    public function getAlerts($type = null){
        $alerts = $this->getSessionData('alerts');
        if($type == null){
            $ret = $alerts;
            $alerts = [];
        }
        if(isset($alerts[$type])){
            $ret = $alerts[$type];
            unset($alerts[$type]);
        }
        $this->setSessionData('alerts',$alerts);
        return $ret;
    }

}