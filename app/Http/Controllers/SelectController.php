<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class SelectController extends BaseController
{
    public function store(Request $request)
    {
        $request->session()->put('selection', $request->selection);

        return response('OK', 200);
    }
}
