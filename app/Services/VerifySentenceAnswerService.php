<?php

namespace App\Services;

use App\Structs\SentenceAnswerStruct;
use Illuminate\Support\Facades\Session;
use App\Repositories\SentenceRepository;

class VerifySentenceAnswerService
{
    public function handle(SentenceAnswerStruct $answer): bool
    {
        $learnLanguage = Session::get('learn-language', 'en');

        $sentenceRepository = new SentenceRepository();
        $correctSentence = $sentenceRepository->getSentenceByAttribute($answer->chosenAttribute);

        return $answer->answerSentence === __($correctSentence, [], $learnLanguage);
    }
}
