<?php

namespace App\Services\QuestionTypes;

use App\Repositories\SentenceRepository;
use App\Services\Interfaces\SentenceInterface;

class MultipleChoiceService implements SentenceInterface
{
    protected $sentenceRepository;

    public function __construct()
    {
        $this->sentenceRepository = new SentenceRepository();
    }

    public function handle(string $chosenAttribute, string $correctSentence): array
    {
        $arrSentences = [];
        $arrSentenceCorrect[$chosenAttribute] = $correctSentence;
        array_push($arrSentences, $arrSentenceCorrect);

        $randomSentence1 = $this->sentenceRepository->getRandomSentenceWithKeys($arrSentences);
        array_push($arrSentences, $randomSentence1);

        $randomSentence2 = $this->sentenceRepository->getRandomSentenceWithKeys($arrSentences);
        array_push($arrSentences, $randomSentence2);

        shuffle($arrSentences);

        return $arrSentences;
    }
}
