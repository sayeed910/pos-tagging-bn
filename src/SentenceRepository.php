<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/11/2018
 * Time: 11:32 PM
 */

namespace App;


class SentenceRepository
{
    /**
     * @var Database
     */
    private $connection;

    public function __construct(Database $connection)
    {
        $this->connection = $connection;
    }

    public function getRandom($count = 1)
    {
        $resultset = $this->connection->prepare("select s_id, sentence from sentences order by rand() limit ". $count);
        $sentences = [];
        foreach ($resultset as $row)
            $sentences[] = Sentence::newInstance($row['sentence'], $row['id']);

        return array_map(function ($row){return Sentence::newInstance($row['sentence'], $row['id']);}, $resultset);

    }

    public function saveTags(Sentence $sentence)
    {


    }

}