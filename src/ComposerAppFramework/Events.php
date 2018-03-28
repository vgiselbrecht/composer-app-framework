<?php
/**
 * Events
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Events
{
    /** @var App */
    var $app = null;
    var $events = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function triggerEvent($eventname, &$data = null){
        if(isset($this->events[$eventname])){
            $listeners = $this->orderByPositon($this->events[$eventname]);
            foreach ($listeners as $listener){
                if($data){
                    call_user_func_array($listener['function'], array(&$data));
                }else{
                    call_user_func($listener['function']);
                }
            }
        }
    }

    public function registerListener($eventname, $function, $position = 0){
        $this->events[$eventname][] = ['function' => $function, 'position' => $position];
    }

    private function orderByPositon($listeners){
        $sort = [];
        $sortedListeners = [];
        foreach ($listeners as $key => $listener){
            $sort[$key] = $listener['position'];
        }
        sort($sort);
        foreach ($sort as $key => $position){
            $sortedListeners[] = $listeners[$key];
        }
        return $sortedListeners;
    }

}