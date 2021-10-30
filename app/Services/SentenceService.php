<?php

namespace App\Services;

use App\Strategies\SentenceStrategy;
use App\Repositories\SentenceRepository;
use App\Services\QuestionTypes\GapService;

class SentenceService
{
    protected $chosenAttribute;
    protected $correctSentence;
    protected $sentenceRepository;

    public function __construct(string $chosenAttributeKey)
    {
        $this->sentenceRepository = new SentenceRepository();
        $this->correctSentence = $this->sentenceRepository->getSentenceByAttribute($chosenAttributeKey, true);
        $this->chosenAttributeKey = $chosenAttributeKey;
    }

    public function handle(string $questionType, string $attributeValue): array
    {
        $strategy = SentenceStrategy::handle($questionType);

        $attribute = $this->chosenAttributeKey;

        if (get_class($strategy) === GapService::class) {
            $attribute = $attributeValue;
        }

        return $strategy->handle($attribute, $this->correctSentence);
    }
}
