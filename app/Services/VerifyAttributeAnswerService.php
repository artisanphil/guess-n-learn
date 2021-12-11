<?php

namespace App\Services;

use App\Models\Attribute;
use App\Structs\AttributeAnswerStruct;

class VerifyAttributeAnswerService
{
    public function handle(AttributeAnswerStruct $answer): bool
    {
        $answerAttribute = Attribute::where('value', $answer->chosenAttribute)
            ->first();

        if (!$answerAttribute) {
            return false;
        }


        $relatedSentence = Attribute::where('value', strtolower($answer->answerAttribute))
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
