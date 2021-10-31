<?php

namespace App\Http\Controllers;

use App\Constants\QuestionType;
use App\Constants\UserType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\GuessService;
use App\Http\Requests\AttributeGuessRequest;
use App\Http\Requests\ObjectGuessRequest;
use App\Http\Requests\VerifyAnswerRequest;
use App\Services\SentenceService;
use App\Services\VerifyAnswerService;
use App\Structs\AttributeAnswerStruct;
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
        $attributeKey = $request->attributeKey;
        $attributeValue = $request->attributeValue;

        $questionType = Arr::random(config('question.types'));

        //for testing purposes
        if (isset($request->questiontype)) {
            $questionType = $request->questiontype;
        }

        $questionType = $questionType;

        $data = (new SentenceService($attributeKey))->handle($questionType, $attributeValue);

        return response($data)
            ->header('Question-Type', $questionType);
    }

    public function attribute(AttributeGuessRequest $request): array
    {
        return $this->guessService->handle($request->choice, UserType::PERSON);
    }

    public function object(ObjectGuessRequest $request): array
    {
        $computer = UserType::COMPUTER;
        $computerSelection = $request->session()->get("{$computer}-selection");

        return [
            'correct' => $request->name === $computerSelection['name']
        ];
    }

    public function verifyAttribute(VerifyAnswerRequest $request): array
    {
        $answer = new AttributeAnswerStruct();
        $answer->chosenAttribute = $request->chosenAttribute;
        $answer->answerAttribute = $request->answerAttribute;

        return [
            'correct' => (new VerifyAnswerService())
                ->handle($answer)
        ];
    }
}
