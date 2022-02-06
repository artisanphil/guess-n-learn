<?php

namespace App\Helpers;

use App\Models\LogAction;
use App\Models\LogAnswer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LogHelper
{
    public static function saveAction(bool $computer, string $action, string $value, int $mistakes = 0): void
    {
        $round = LogAction::where('session_id', Session::getId())
            ->max('round');

        if($action == 'learn-language') {
            $round = LogAction::max('round') + 1 ?? 1;
        }

        LogAction::create([
            'round' => $round,
            'IP' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? 'unknown',
            'session_id' => Session::getId(),
            'user' => $computer ? 'computer' : 'user',
            'action' => $action,
            'value' => $value,
            'mistakes' => $mistakes
        ]);
    }

    public static function saveQuestion(string $type, string $attribute, string $input, bool $correct): int
    {
        $round = LogAction::where('session_id', Session::getId())
            ->max('round');

        return LogAnswer::create([
            'round' => $round,
            'type' => $type,
            'attribute' => $attribute,
            'input' => $input,
            'correct' => $correct,
        ])->id;
    }
}
