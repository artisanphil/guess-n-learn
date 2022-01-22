<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Constants\UserType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\GuessService;
use App\Constants\QuestionType;
use App\Services\SentenceService;
use App\Structs\SentenceAnswerStruct;
use App\Structs\AttributeAnswerStruct;
use Illuminate\Support\Facades\Session;
use App\Repositories\SentenceRepository;
use App\Http\Requests\ObjectGuessRequest;
use App\Http\Requests\AttributeGuessRequest;
use App\Http\Requests\VerifySentenceRequest;
use App\Http\Requests\VerifyAttributeRequest;
use App\Services\VerifySentenceAnswerService;
use App\Services\VerifyAttributeAnswerService;
use App\Http\Requests\GetCorrectSentenceRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

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

        LogHelper::saveAction(false, 'character-guess', $request->name);
        LogHelper::saveAction(false, 'wins', $request->name === $computerSelection['name']);

        return [
            'correct' => $request->name === $computerSelection['name']
        ];
    }

    public function remainingObjects(): array
    {
        if (!Session::get("remaining-person-objects")) {
            return [];
        }

        return Arr::pluck(Session::get("remaining-person-objects"), 'name');
    }

    public function verifyAttribute(VerifyAttributeRequest $request): array
    {
        $answer = new AttributeAnswerStruct();
        $answer->chosenAttribute = $request->chosenAttribute;
        $answer->answerAttribute = $request->answerAttribute;
        $correct = (new VerifyAttributeAnswerService())
            ->handle($answer, $request->type);

        $questionNr = LogHelper::saveQuestion($request->type, $request->chosenAttribute, $request->answerAttribute, $correct);
        LogHelper::saveAction(false, 'question', $questionNr);

        return [
            'correct' => $correct
        ];
    }

    public function verifySentence(VerifySentenceRequest $request): array
    {
        $answer = new SentenceAnswerStruct();
        $answer->chosenAttribute = $request->chosenAttribute;
        $answer->answerSentence = $request->answerSentence;
        $correct = (new VerifySentenceAnswerService())
            ->handle($answer);

        $questionNr = LogHelper::saveQuestion('drag-drop', $request->chosenAttribute, $request->answerSentence, $correct);
        LogHelper::saveAction(false, 'question', $questionNr);

        return [
            'correct' => $correct
        ];
    }

    public function correctSentence(GetCorrectSentenceRequest $request): array
    {
        $learnLanguage = Session::get('learn-language', 'en');

        $sentenceRepository = new SentenceRepository();
        $correctSentence = $sentenceRepository->getSentenceByAttribute($request->chosenAttribute);

        return [
            'sentence' => __($correctSentence, [], $learnLanguage)
        ];
    }
}
