<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class SelectController extends BaseController
{
    public function index()
    {
        $objects = file_get_contents(base_path('resources/json/characters.json'));

        return json_decode($objects, true);
    }

    public function store(Request $request)
    {
        $request->session()->put('selection', $request->selection);

        return response('OK', 200);
    }
}
