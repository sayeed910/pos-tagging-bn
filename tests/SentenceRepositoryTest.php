<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/12/2018
 * Time: 3:17 PM
 */

namespace App;

require_once __DIR__ . '/../src/sentences.php';

use PHPUnit\Framework\TestCase;

class SentenceRepositoryTest extends TestCase
{
    public function testGetRandomReturnsSentences()
    {
        $repo = new SentenceRepository(new Database());
        $result = $repo->getRandom(10);

        self::assertCount(10, $result);
        $aSentence = $result[5];

        $this->assertNotFalse(search($aSentence->getSentence()));



    }

    public function testPregMatch()
    {
        $pattern = "@$@";
        self::assertEquals(1, preg_match($pattern, "http://localhost/pos-tagging/public/pos-tagging"));
    }

}
