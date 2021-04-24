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

    public function handle(string $chosenAttribute, string $correctSentence): array
    {
        $sentence = str_replace($chosenAttribute, "{gap}", $correctSentence);

        return [
            $sentence
        ];
    }
}
