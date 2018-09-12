<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/11/2018
 * Time: 11:53 PM
 */

declare(strict_types=1);

namespace App;

use PHPUnit\Framework\TestCase;

class SentenceTest extends TestCase{
    public function testCanBeSerializedToJson(): void
    {
        $sentence = Sentence::newInstance('A Quick Brown Fox', '12345');
        
        $sentence_json = json_encode($sentence);
        $expected_json = json_encode([
            'id' => '12345',
            'sentence' => 'A Quick Brown Fox',
        ]);
        
        $this->assertEquals($expected_json, $sentence_json);



    }



}