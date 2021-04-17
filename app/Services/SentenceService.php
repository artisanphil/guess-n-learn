<?php

namespace App\Services;

use App\Constants\UserType;
use Illuminate\Support\Arr;
use App\Constants\QuestionType;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;
use App\Repositories\SentenceRepository;

class SentenceService
{
    protected $chosenAttribute;
    protected $correctSentence;
    protected $sentenceRepository;

    public function __construct(string $chosenAttribute)
    {
        $this->sentenceRepository = new SentenceRepository();
        $this->correctSentence = $this->sentenceRepository->getSentenceByAttribute($chosenAttribute);
        $this->chosenAttribute = $chosenAttribute;
    }

    public function handle(string $questionType): array
    {
        if ($questionType === QuestionType::MCHOICE) {
            return $this->getMultipleChoiceSentences();
        }
    }

    protected function getMultipleChoiceSentences(): array
    {
        $arrSentences = [];
        $arrSentenceCorrect[$this->chosenAttribute] = $this->correctSentence;
        array_push($arrSentences, $arrSentenceCorrect);

        $randomSentence1 = $this->sentenceRepository->getRandomSentenceWithKeys($arrSentences);
        array_push($arrSentences, $randomSentence1);

        $randomSentence2 = $this->sentenceRepository->getRandomSentenceWithKeys($arrSentences);
        array_push($arrSentences, $randomSentence2);

        shuffle($arrSentences);

        return $arrSentences;
    }
}
