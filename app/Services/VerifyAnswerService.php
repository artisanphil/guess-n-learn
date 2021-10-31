<?php

namespace App\Services;

use App\Structs\AttributeAnswerStruct;

class VerifyAnswerService
{
    public function handle(AttributeAnswerStruct $answer): bool
    {
        $answerAttribute = strtolower($answer->answerAttribute);

        return $answerAttribute === $answer->chosenAttribute;
    }
}
