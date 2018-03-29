<?php
/**
 * Session
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


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

    /**
     * set session data
     * @param $key
     * @param $value
     */
    public function setSessionData($key, $value){
        $_SESSION[$this->app->config['appkey']][$key] = $value;
    }

    /**
     * get session data
     * @param $key
     * @return mixed
     */
    public function getSessionData($key){
        if(isset($_SESSION[$this->app->config['appkey']][$key] )){
            return $_SESSION[$this->app->config['appkey']][$key];
        }
    }

    /**
     * function to remember user id
     * @param $userId
     */
    public function setUserId($userId){
        $this->setSessionData("user_id", $userId);
    }

    /**
     * function to get user id
     * @return mixed
     */
    public function getUserId(){
        return $this->getSessionData("user_id");
    }

    /**
     * save alert information
     * @param $msg
     * @param $type
     */
    public function setAlert($msg, $type){
        $alerts = $this->getSessionData('alerts');
        $alerts[$type][] = $msg;
        $this->setSessionData('alerts', $alerts);
    }

    /**
     * get alert information
     * @param null $type
     * @return mixed
     */
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