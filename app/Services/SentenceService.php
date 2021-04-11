<?php

namespace App\Services;

use App\Constants\QuestionType;
use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;

class SentenceService
{
    protected $chosenAttribute;
    protected $correctSentence;

    public function __construct(string $chosenAttribute)
    {
        $objectRepository = new ObjectRepository();
        $this->correctSentence = $objectRepository->getSentenceByAttribute($chosenAttribute);
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
        return [$this->correctSentence];
    }
}
