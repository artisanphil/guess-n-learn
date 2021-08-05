<?php

namespace App\Services\QuestionTypes;

use App\Repositories\SentenceRepository;
use App\Services\Interfaces\SentenceInterface;

class MultipleChoiceService implements SentenceInterface
{
    protected $sentenceRepository;
    protected array $arrSentences = [];

    public function __construct()
    {
        $this->sentenceRepository = new SentenceRepository();
    }

    public function handle(string $chosenAttribute, string $correctSentence): array
    {
        $arrSentenceCorrect[$chosenAttribute] = $correctSentence;
        $this->addSentence($arrSentenceCorrect);

        $randomSentence1 = $this->sentenceRepository->getRandomSentenceWithKeys($this->arrSentences);
        $this->addSentence($randomSentence1);

        $randomSentence2 = $this->sentenceRepository->getRandomSentenceWithKeys($this->arrSentences);
        $this->addSentence($randomSentence2);

        shuffle($this->arrSentences);

        return $this->arrSentences;
    }

    protected function addSentence(array $arrSentence): void
    {
        $key = key($arrSentence);
        $arrSentenceCorrect['attribute'] = $key;
        $arrSentenceCorrect['sentence'] = $arrSentence[$key];

        array_push($this->arrSentences, $arrSentenceCorrect);
    }
}
