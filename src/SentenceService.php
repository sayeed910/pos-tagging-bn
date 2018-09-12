<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/12/2018
 * Time: 1:02 PM
 */

namespace App;

class SentenceService
{
    /**
     * @var SentenceRepository
     */
    private $repository;

    public function __construct(SentenceRepository $repository)
    {

        $this->repository = $repository;
    }

    /**
     * @param int $count
     * @return Sentence[]
     */
    public function getRandomSentences($count=1): Array
    {
        return $this->repository->getRandom($count);

    }

}