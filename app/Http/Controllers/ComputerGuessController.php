<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComputerGuessRequest;
use Illuminate\Support\Arr;
use App\Services\GuessService;
use App\Repositories\ObjectRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ComputerGuessController extends BaseController
{
    protected $guessService;
    protected $objectRepository;

    public function __construct()
    {
        $this->guessService = new GuessService();
        $this->objectRepository = new ObjectRepository();
    }

    public function index()
    {
        $attributes = $this->objectRepository->getRemainingAttributes();
        $chosenAttribute = Arr::random($attributes);

        return $this->objectRepository->getSentenceByAttribute($chosenAttribute);
    }

    public function store(ComputerGuessRequest $request)
    {
        return $this->guessService->handle($request->choice);
    }
}
