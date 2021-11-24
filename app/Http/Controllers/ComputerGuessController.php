<?php

namespace App\Http\Controllers;

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

    public function __construct()
    {
        $this->guessService = new GuessService();
        $this->objectRepository = new ObjectRepository();
        $this->sentenceRepository = new SentenceRepository();
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
        } else {
            $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);
            $chosenAttribute = Arr::random($attributes);
            $sentence = $this->sentenceRepository->getSentenceByAttribute($chosenAttribute);
        }

        return [
            'sentence' => $sentence,
            'choice' => $chosenAttribute
        ];
    }

    public function store(ComputerGuessRequest $request): array
    {
        return $this->guessService->handle($request->choice);
    }
}
