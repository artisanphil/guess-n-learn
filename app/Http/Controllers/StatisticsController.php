<?php

namespace App\Http\Controllers;

use App\Models\LogAction;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends BaseController
{
    public function visitsday()
    {
        return DB::select(
            DB::raw('SELECT COUNT(distinct IP), COUNT(distinct session_id), date(created_at) 
            FROM guess_n_learn.log_actions 
            GROUP BY date(created_at)
            ORDER BY date(created_at) DESC
            LIMIT 30')
        );
    }

    public function leaderboard()
    {
        $turns = LogAction::where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())
            ->where('action', 'wins')
            ->where('value', 1)
            ->groupBy('session_id')
            ->orderBy(DB::raw('count(session_id)', 'DESC'))
            ->selectRaw('session_id, count(session_id) as turnsCount')
            ->get();

        $leaderboard = [];
        $i = 0;
        foreach($turns as $turn) {
            $mistakes = LogAction::where('session_id', $turn->session_id)
                ->selectRaw('sum(mistakes) as mistakes')
                ->first()
                ->mistakes;

            $leaderboard[$i]['turns'] = $turn->turnsCount;     
            $leaderboard[$i]['session_id'] = $turn->session_id;     
            $leaderboard[$i]['mistakes'] = $mistakes;     
            $i++;
        }

        $leaderBoardSorted = collect($leaderboard)
            ->sortBy('mistakes')
            ->sortByDesc('turns');
            
        return $leaderBoardSorted;
    }
}
