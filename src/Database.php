<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/12/2018
 * Time: 12:28 PM
 */

namespace App;
require_once __DIR__ . './sentences.php';

class Database
{

    public function execute(string $query) : array
    {
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

}