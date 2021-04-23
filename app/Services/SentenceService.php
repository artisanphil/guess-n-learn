<?php

namespace App\Services;

use App\Constants\QuestionType;
use App\Strategies\SentenceStrategy;
use App\Repositories\SentenceRepository;
use App\Services\QuestionTypes\MultipleChoiceService;

class SentenceService
{
    protected $chosenAttribute;
    protected $correctSentence;
    protected $sentenceRepository;

    const MAPPING = [
        QuestionType::MCHOICE => MultipleChoiceService::class,
    ];

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
