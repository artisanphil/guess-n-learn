<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserGuessRequest;
use Illuminate\Routing\Controller as BaseController;

class UserGuessController extends BaseController
{
    public function store(UserGuessRequest $request)
    {
        dump($request->choice);
    }
}
