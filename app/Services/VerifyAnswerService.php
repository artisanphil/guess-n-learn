<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\SentenceAttribute;
use App\Structs\AttributeAnswerStruct;

class VerifyAnswerService
{
    public function handle(AttributeAnswerStruct $answer): bool
    {
        $answerAttribute = Attribute::where('attribute', $answer->chosenAttribute)
            ->first();

        if (!$answerAttribute) {
            return false;
        }


        $relatedSentence = Attribute::where('attribute', strtolower($answer->answerAttribute))
            ->first();

        if (!$relatedSentence) {
            return false;
        }

        $answerSentenceId = $answerAttribute->relatedSentence()
            ->first()
            ->id;

        $relatedSentenceId = $relatedSentence->relatedSentence()
            ->first()
            ->id;

        return $answerSentenceId === $relatedSentenceId;
    }
}
