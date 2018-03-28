<?php
/**
 * Database
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Database
{

    var $config = [];
    var $mysqli = null;

    public function __construct()
    {
        return $this;
    }

    public function setDbConfig($config){
        if(isset($config['db'])){
            $this->config = $config['db'];
        }
    }

    /**
     * return db config in doctrin format
     * @return mixed
     */
    public function getDoctrinParameters(){
        $connectionParams['driver'] = 'pdo_mysql';
        if(isset($this->config['database'])){
            $connectionParams['dbname'] = $this->config['database'];
        }
        if(isset($this->config['database'])){
            $connectionParams['user'] = $this->config['user'];
        }
        if(isset($this->config['database'])){
            $connectionParams['password'] = $this->config['password'];
        }
        if(isset($this->config['database'])){
            $connectionParams['host'] = $this->config['host'];
        }
        return $connectionParams;
    }

    /**
     * return a mysqli db connection
     * @return \mysqli|null
     */
    public function getMysqliConnection(){
        if(!$this->mysqli){
            $this->mysqli = mysqli_connect(
                $this->config['host'],
                $this->config['user'],
                $this->config['password'],
                $this->config['database']);
            $this->mysqli->query("SET NAMES 'utf8'");
        }
        return $this->mysqli;
    }

}