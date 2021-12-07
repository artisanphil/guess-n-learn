<?php

namespace App\Repositories;

use App\Models\Attribute;
use Illuminate\Support\Facades\Session;

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

        $sentence = Attribute::where('attribute', $attribute)
            ->first()
            ->relatedSentence()
            ->first();

        $sentenceValue = $sentence->value;

        if (substr($this->learnLanguage, 0, 2) !== 'en') {
            $sentenceValue = $sentence->translations()
                ->byLanguage($this->learnLanguage)
                ->first()
                ->value;
        }

        return __($sentenceValue, [], $this->learnLanguage);
    }

    public function getRandomSentenceWithKeys(array $excludeSentences): array
    {
        $excludeAttributes = array_column($excludeSentences, 'attribute');

        $attributeData = Attribute::whereNotIn('attribute', $excludeAttributes)
            ->inRandomOrder()
            ->first();

        $sentence = $attributeData->relatedSentence()
            ->first()
            ->sentence;

        return [
            'sentence' => __($sentence, [], $this->learnLanguage),
            'attribute' => $attributeData->attribute,
        ];
    }
}
