<?php

namespace App\Services\QuestionTypes;

use App\Repositories\SentenceRepository;
use App\Services\Interfaces\SentenceInterface;

class GapService implements SentenceInterface
{
    protected $sentenceRepository;

    public function __construct()
    {
        $this->sentenceRepository = new SentenceRepository();
    }

    public function handle(string $chosenAttributeValue, string $correctSentence): array
    {
        $sentence = preg_replace('/{(.*?)}/', '{gap}', $correctSentence);

        return [
            $sentence
        ];
    }
}
