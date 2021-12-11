<?php

namespace App\Repositories;

use App\Models\Attribute;
use Illuminate\Support\Facades\Session;
use App\Models\Sentence;

class SentenceRepository
{
    protected $learnLanguage;

    public function __construct()
    {
        $this->learnLanguage = Session::get('learn-language', 'en');
    }

    public function getSentenceByAttribute(string $attribute): string
    {
        $attribute = strtolower($attribute);

        $sentence = Attribute::where('value', $attribute)
            ->first()
            ->relatedSentence()
            ->first();

        $sentenceValue = $this->getSentenceValue($sentence);

        return __($sentenceValue, [], $this->learnLanguage);
    }

    public function getRandomSentenceWithKeys(array $excludeSentences): array
    {
        $excludeAttributes = array_column($excludeSentences, 'attribute');

        $attributeData = Attribute::whereNotIn('value', $excludeAttributes)
            ->inRandomOrder()
            ->first();

        $sentence = $attributeData->relatedSentence()
            ->first();

        $sentenceValue = $this->getSentenceValue($sentence);

        return [
            'sentence' => __($sentenceValue, [], $this->learnLanguage),
            'attribute' => $attributeData->value,
        ];
    }

    protected function getSentenceValue(Sentence $sentence): string
    {
        if (substr($this->learnLanguage, 0, 2) === 'en') {
            return $sentence->value;
        }

        return $sentence->translations()
            ->byLanguage($this->learnLanguage)
            ->first()
            ->value;
    }
}
