<?php

namespace App\Services;

use App\Strategies\SentenceStrategy;
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
        $strategy = SentenceStrategy::handle($questionType);

        return $strategy->handle($this->chosenAttribute, $this->correctSentence);
    }
}
