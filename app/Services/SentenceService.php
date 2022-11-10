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
        $this->chosenAttributeKey = $chosenAttributeKey;
    }

    public function handle(string $questionType, string $attributeValue): array
    {
        $strategy = SentenceStrategy::handle($questionType);
        $removeGap = true;
        $attribute = $this->chosenAttributeKey;

        if (get_class($strategy) === GapService::class) {
            $attribute = $attributeValue;
            $removeGap = false;
        }

        $correctSentence = $this->sentenceRepository->getSentenceByAttribute($this->chosenAttributeKey, $removeGap);

        return $strategy->handle($attribute, $correctSentence);
    }
}
