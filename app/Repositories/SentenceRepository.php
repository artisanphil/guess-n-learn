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

    public function getSentenceByAttribute(string $attribute, bool $translate): string
    {
        $attribute = strtolower($attribute);

        if (!in_array($attribute, array_keys($this->sentencesWithKeys))) {
            return '';
        }

        $key = array_search($attribute, array_column($this->sentencesWithKeys, 'attribute'));

        if ($translate) {
            return __($this->sentencesWithKeys[$key]['sentence'], [], $this->learnLanguage);
        }

        return $this->sentencesWithKeys[$key]['sentence'];
    }

    public function getRandomSentenceWithKeys(array $excludeSentences): array
    {
        $excludeAttributes = array_column($excludeSentences, 'attribute');

        foreach ($excludeAttributes as $excludeAttribute) {
            $key = array_search($excludeAttribute, array_column($this->sentencesWithKeys, 'attribute'));
            unset($this->sentencesWithKeys[$key]);
        }

        $randomSentenceKey = array_rand($this->sentencesWithKeys);

        return $this->sentencesWithKeys[$randomSentenceKey];
    }
}
