<?php
/**
 * Model
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace Staempfli\Voucher\Library;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


class Model
{

    /** @var App */
    var $app = null;
    var $entityManager = null;
    var $singeltons = [];

    public function __construct($app)
    {
        $this->app = $app;
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration(array(realpath(dirname(__FILE__))."/../Model"), $isDevMode);
        $this->entityManager = EntityManager::create($this->app->getDB()->getDoctrinParameters(), $config);
    }

    public function saveModel($entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function getRepository($name){
        return $this->entityManager->getRepository($name);
    }

    public function deleteModel($entity){
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function getSingelton($name){
        if(!isset($singeltons[$name])){
            if(is_subclass_of($name, "Staempfli\\Voucher\\Library\\Singelton")) {
                $singeltons[$name] = new $name($this->app);
            }
        }
        if(!isset($singeltons[$name])){
            throw new \Exception('Class '.$name.' not exist or was not a singelton!');
        }
        return $singeltons[$name];
    }

    public function getEntityManager(){
        return $this->entityManager;
    }
}