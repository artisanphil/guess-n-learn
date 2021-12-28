<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Constants\UserType;
use Illuminate\Support\Arr;
use App\Services\GuessService;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;
use App\Repositories\SentenceRepository;
use App\Http\Requests\ComputerGuessRequest;
use Illuminate\Routing\Controller as BaseController;

class ComputerGuessController extends BaseController
{
    protected $guessService;
    protected $objectRepository;
    protected $sentenceRepository;
    protected $learnLanguage;

    public function __construct()
    {
        $this->guessService = new GuessService();
        $this->objectRepository = new ObjectRepository();
        $this->sentenceRepository = new SentenceRepository();
        $this->learnLanguage = Session::get('learn-language', 'en');
    }

    public function index(): array
    {
        $chosenAttribute = "";
        $sentence = "";
        $guesser = UserType::COMPUTER;
        $remainingObjects = Session::get("remaining-{$guesser}-objects");

        if (isset($remainingObjects) && count($remainingObjects) == 1) {
            $objects = Session::get("remaining-{$guesser}-objects");
            $name = $objects[0]['name'];
            $sentence = "Your person is {$name}";
            LogHelper::saveAction(true, 'character-guess', $name);
        } else {
            $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);
            $chosenAttribute = Arr::random($attributes);
            $sentence = $this->sentenceRepository->getSentenceByAttribute($chosenAttribute);
        }

        LogHelper::saveAction(true, 'choice', $chosenAttribute);

        return [
            'sentence' => $sentence,
            'choice' => $chosenAttribute,
            'No' => __('No', [], $this->learnLanguage),
            'Yes' => __('Yes', [], $this->learnLanguage),
        ];
    }

    public function store(ComputerGuessRequest $request): array
    {
        return $this->guessService->handle($request->choice);
    }
}
