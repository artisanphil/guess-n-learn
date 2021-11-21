<?php

namespace App\Services;

use App\Models\Attribute;
use App\Structs\SentenceAnswerStruct;

class VerifySentenceAnswerService
{
    public function handle(SentenceAnswerStruct $answer): bool
    {
        $correctSentence = Attribute::where('attribute', strtolower($answer->chosenAttribute))
            ->first()
            ->relatedSentence()
            ->first()
            ->sentence;

        return $answer->answerSentence === $correctSentence;
    }
}
