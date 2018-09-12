<?php
/**
 * Created by PhpStorm.
 * User: Tahsin Sayeed
 * Date: 9/11/2018
 * Time: 11:39 PM
 */

namespace App;


class Sentence implements \JsonSerializable
{
    private $sentence;
    private $wordlist;
    private $tags;
    private $sentenceId;

    /**
     * Sentence constructor.
     * @param $sentenceId
     * @param $sentence
     * @param $wordlist
     * @param $tags
     */
    public function __construct($sentenceId, $sentence, $wordlist, $tags)
    {
        $this->sentence = $sentence;
        $this->wordlist = $wordlist;
        $this->tags = $tags;

        $this->sentenceId = $sentenceId;
    }


    public static function newInstance(string $sentence, string $sentenceId=null){
        $words = explode(" ", $sentence);
        return new Sentence($sentenceId, $sentence, $words, []);
    }

    /**
     * @return mixed
     */
    public function getSentence()
    {
        return $this->sentence;
    }

    /**
     * @return mixed
     */
    public function getWordlist()
    {
        return $this->wordlist;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return mixed
     */
    public function getSentenceId()
    {
        return $this->sentenceId;
    }

    /**
     * @param mixed $sentenceId
     */
    public function setId(string $sentenceId)
    {
        if (is_null($this->sentenceId))
            $this->sentenceId = $sentenceId;
        else throw new \InvalidArgumentException("Sentence Id is immutable once set");
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->sentenceId,
            'sentence' => $this->sentence
        ];
    }

    public function __toString()
    {
        return sprintf("Sentence(id=%s, sentence=%s)", $this->sentenceId, $this->sentence);
    }


}