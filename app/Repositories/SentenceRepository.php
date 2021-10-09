<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class SentenceRepository
{
    protected $sentencesWithKeys;
    protected $learnLanguage;

    public function __construct()
    {
        $sentencesJson = file_get_contents(base_path('resources/json/character-sentences_en.json'));

        $this->sentencesWithKeys = json_decode($sentencesJson, true);
        $this->learnLanguage = Session::get('learn-language', 'en');
    }

    public function getSentenceByAttribute(string $attribute): string
    {
        return __($this->sentencesWithKeys[$attribute], [], $this->learnLanguage);
    }

    public function getRandomSentenceWithKeys(array $excludeSentences)
    {
        $excludeAttributes = array_column($excludeSentences, 'attribute');
        $sentences = Arr::except($this->sentencesWithKeys, $excludeAttributes);

        $randomSentenceKey = array_rand($sentences);

        return [
            $randomSentenceKey => __($sentences[$randomSentenceKey], [], $this->learnLanguage)
        ];
    }
}
