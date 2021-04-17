<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class SentenceRepository
{
    protected $sentencesWithKeys;

    public function __construct()
    {
        $sentencesJson = file_get_contents(base_path('resources/json/character-sentences_en.json'));

        $this->sentencesWithKeys = json_decode($sentencesJson, true);
    }

    public function getSentenceByAttribute(string $attribute): string
    {
        return $this->sentencesWithKeys[$attribute];
    }

    public function getRandomSentenceWithKeys(array $excludeSentences)
    {
        $excludeAttributes = array_keys($excludeSentences);
        $sentences = Arr::except($this->sentencesWithKeys, $excludeAttributes);

        $randomSentenceKey = array_rand($sentences);

        return [
            $randomSentenceKey => $sentences[$randomSentenceKey]
        ];
    }
}
