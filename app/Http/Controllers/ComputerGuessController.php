<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComputerGuessRequest;
use Illuminate\Support\Arr;
use App\Services\GuessService;
use App\Repositories\ObjectRepository;
use App\Constants\UserType;
use App\Repositories\SentenceRepository;
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

    public function index()
    {
        $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);
        $chosenAttribute = Arr::random($attributes);

        return [
            'sentence' => $this->sentenceRepository->getSentenceByAttribute($chosenAttribute),
            'choice' => $chosenAttribute
        ];
    }

    public function store(ComputerGuessRequest $request)
    {
        return $this->guessService->handle($request->choice);
    }
}
