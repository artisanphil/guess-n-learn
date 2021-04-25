<?php

namespace App\Strategies;

use Illuminate\Support\Arr;
use App\Constants\QuestionType;
use App\Services\Interfaces\SentenceInterface;
use App\Services\QuestionTypes\DragDropService;
use App\Services\QuestionTypes\GapService;
use App\Services\QuestionTypes\MultipleChoiceService;
use Exception;

class SentenceStrategy
{
    const MAPPING = [
        QuestionType::MCHOICE => MultipleChoiceService::class,
        QuestionType::GAP => GapService::class,
        QuestionType::DRAGDROP => DragDropService::class
    ];

    public static function handle(string $questionType): SentenceInterface
    {
        $strategy = static::getStrategy($questionType);

        return new $strategy();
    }

    protected static function getStrategy(string $questionType): string
    {
        $class = Arr::get(static::MAPPING, $questionType);

        if ($class) {
            return $class;
        }

        throw new Exception("Invalid question type given {$questionType}");
    }
}
