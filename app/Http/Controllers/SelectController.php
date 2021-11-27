<?php

namespace App\Http\Controllers;

use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use App\Http\Requests\SelectionRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;

class SelectController extends BaseController
{
    protected $objectRepository;

    public function __construct()
    {
        $this->objectRepository = new ObjectRepository();
    }

    public function index()
    {
        return $this->objectRepository->getObjects();
    }

    public function show(): array
    {
        $person = UserType::PERSON;

        return Session::get("{$person}-selection");
    }

    public function store(SelectionRequest $request): array
    {
        $userSelection = $this->objectRepository->getObjectByName($request->selection);
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);

        $computerSelection = $this->objectRepository->getComputerSelection();
        $computer = UserType::COMPUTER;
        $computerSelection = $request->session()->put("{$computer}-selection", $computerSelection);

        return [];
    }
}
