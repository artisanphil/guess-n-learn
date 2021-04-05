<?php

namespace App\Http\Controllers;

use App\Services\GuessService;
use App\Http\Requests\UserGuessRequest;
use App\Constants\UserType;
use Illuminate\Routing\Controller as BaseController;

class UserGuessController extends BaseController
{
    public function __construct()
    {
        $this->guessService = new GuessService();
    }

    public function store(UserGuessRequest $request)
    {
        return $this->guessService->handle($request->choice, UserType::PERSON);
    }
}
