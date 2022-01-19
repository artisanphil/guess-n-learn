<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class StatisticsController extends BaseController
{
    public function visitsday()
    {
        return DB::select(
            DB::raw('SELECT COUNT(distinct IP), date(created_at) 
            FROM guess_n_learn.log_actions 
            GROUP BY date(created_at)
            ORDER BY date(created_at) DESC
            LIMIT 30')
        );
    }
}
