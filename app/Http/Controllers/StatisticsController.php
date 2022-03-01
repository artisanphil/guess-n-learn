<?php

namespace App\Http\Controllers;

use App\Models\LogAction;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        $turns = LogAction::where('created_at', '>=', Carbon::now()->subDays(1)->toDateTimeString())
            ->where('action', 'wins')
            ->where('value', 1)
            ->groupBy(['session_id', 'name'])
            ->orderBy(DB::raw('count(session_id)', 'DESC'))
            ->selectRaw('session_id, name, count(session_id) as turnsCount')
            ->get();

        $leaderboard = [];
        $i = 0;
        foreach($turns as $turn) {
            $lastRound = LogAction::where('session_id', $turn->session_id)
                ->where('name', $turn->name)
                ->max('round');
            $mistakes = LogAction::where('session_id', $turn->session_id)
                ->where('round', $lastRound)
                ->where('name', $turn->name)
                ->selectRaw('sum(mistakes) as mistakes')
                ->first()
                ->mistakes;

            $leaderboard[$i]['turns'] = $turn->turnsCount;     
            $leaderboard[$i]['name'] = $turn->name;     
            $leaderboard[$i]['mistakes'] = $mistakes;     
            $leaderboard[$i]['active'] = $turn->session_id === Session::getId() && $turn->name === Session::get("your-name");
            $i++;
        }

        $leaderBoardSorted = collect($leaderboard)
            ->sortBy('mistakes')
            ->sortByDesc('turns')
            ->values();
            
        return $leaderBoardSorted;
    }
}
