<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/12/2018
 * Time: 4:51 PM
 */

namespace App;


use Prophecy\Exception\Doubler\ClassNotFoundException;

class Registry
{
    private static  $elements;


    public static function init()
    {
        $database = new Database();
        $sentenceRepository = new SentenceRepository($database);
        $sentenceService = new SentenceService($sentenceRepository);

        self::$elements = [
            Database::class => $database,
            SentenceRepository::class => $sentenceRepository,
            SentenceService::class => $sentenceService
        ];
    }

    public static function getInstance($classname){
        if (array_key_exists($classname, self::$elements)){
            return self::$elements[$classname];
        } else {
            throw new ClassNotFoundException("The class does not exist in registry", $classname);
        }
    }


    public static function setInstance($key, $instance)
    {
        self::$elements[$key]  = $instance;
    }

}

Registry::init();
