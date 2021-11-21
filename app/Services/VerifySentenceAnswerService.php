<?php

namespace App\Services;

use App\Models\Attribute;
use App\Structs\SentenceAnswerStruct;
use Illuminate\Support\Facades\Session;

class VerifySentenceAnswerService
{
    public function handle(SentenceAnswerStruct $answer): bool
    {
        $learnLanguage = Session::get('learn-language', 'en');
        $correctSentence = Attribute::where('attribute', strtolower($answer->chosenAttribute))
            ->first()
            ->relatedSentence()
            ->first()
            ->sentence;

        return $answer->answerSentence === __($correctSentence, [], $learnLanguage);
    }
}
