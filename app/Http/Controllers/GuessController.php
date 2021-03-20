<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuessRequest;
use Illuminate\Support\Arr;
use App\Services\GuessService;
use App\Repositories\ObjectRepository;
use Illuminate\Routing\Controller as BaseController;

class GuessController extends BaseController
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

        return $this->guessService->handle($chosenAttribute);
    }

    public function store(GuessRequest $request)
    {
        dump($request->guess);
    }
}
