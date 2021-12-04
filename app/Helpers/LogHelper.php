<?php

namespace App\Helpers;

use App\Models\LogAction;
use App\Models\LogAnswer;

class LogHelper
{
    public static function saveAction(bool $computer, string $action, string $value): void
    {
        LogAction::create([
            'IP' => $_SERVER['REMOTE_ADDR'],
            'user' => $computer ? 'computer' : 'user',
            'action' => $action,
            'value' => $value,
        ]);
    }

    public static function saveQuestion(string $type, string $attribute, string $input, bool $correct): int
    {
        return LogAnswer::create([
            'type' => $type,
            'attribute' => $attribute,
            'input' => $input,
            'correct' => $correct,
        ])->id;
    }
}
