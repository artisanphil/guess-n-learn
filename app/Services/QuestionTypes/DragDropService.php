<?php

namespace App\Services\QuestionTypes;

use App\Repositories\SentenceRepository;
use App\Services\Interfaces\SentenceInterface;

class DragDropService implements SentenceInterface
{
    protected $sentenceRepository;

    public function __construct()
    {
        $this->sentenceRepository = new SentenceRepository();
    }

    public function handle(string $chosenAttribute, string $correctSentence): array
    {
        $correctSentence = str_replace('?', '', $correctSentence);
        $correctGaps = explode(' ', $correctSentence);

        $randomSentence1WithKey = $this->sentenceRepository->getRandomSentenceWithKeys([$correctSentence]);
        $randomSentence1 = array_values($randomSentence1WithKey);
        $randomSentence1 = str_replace('?', '', $randomSentence1);
        $additionalGaps = explode(' ', $randomSentence1[0]);
        $gaps = array_unique(array_merge($correctGaps, $additionalGaps));

        shuffle($gaps);

        return array_values($gaps);
    }
}
