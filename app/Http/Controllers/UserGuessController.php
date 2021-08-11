<?php

namespace App\Http\Controllers;

use App\Constants\QuestionType;
use App\Constants\UserType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\GuessService;
use App\Http\Requests\UserGuessRequest;
use App\Services\SentenceService;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class UserGuessController extends BaseController
{
    public function __construct()
    {
        $this->guessService = new GuessService();
    }

    public function index(Request $request): Response
    {
        $attribute = $request->choice;
        //$questionType = Arr::random(config('question.types'));
        $questionType = QuestionType::MCHOICE;

        $data = (new SentenceService($attribute))->handle($questionType);

        return response($data)
            ->header('Question-Type', $questionType);
    }

    public function store(UserGuessRequest $request): array
    {
        return $this->guessService->handle($request->choice, UserType::PERSON);
    }
}
