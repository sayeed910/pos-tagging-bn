<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/12/2018
 * Time: 12:28 PM
 */

namespace App;
require_once __DIR__ . './sentences.php';
$dbconfig = require_once __DIR__ . '../database/config.php';

class Database
{
    private $connection;
    /** @var $lastStatement \PDOStatement */
    private $lastStatement;
    public function __construct()
    {
        global $dbconfig;
        $server = $dbconfig['HOST_NAME'];
        $user = $dbconfig['USER_NAME'];
        $dbname = $dbconfig['DATABASE_NAME'];
        $password = $dbconfig['PASSWORD'];
        try{
            $this->connection = new \PDO("mysql:host=$server;dbname=$dbname", $user, $password );
        } catch (\Exception $ex){
            var_dump($ex);
        }
        $this->lastStatement = null;
    }



    public function prepare(string $query) : array
    {
//        $this->lastStatement = $this->connection->prepare($query);
        $sentences = get_sentences(10);
        $result = [];

        foreach ($sentences as $sentence){
            $result[] = [
                'id' => uniqid("", true),
                'sentence' => $sentence
            ];
        }
        
        return $result;


    }

    public function bindParam(string $param, $value)
    {
       $this->lastStatement->bindParam($param, $value);
    }

    public function execute()
    {
        $this->lastStatement->execute();
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function endTransaction()
    {
        $this->connection->commit();
    }

}