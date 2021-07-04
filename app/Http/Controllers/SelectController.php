<?php

namespace App\Http\Controllers;

use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use App\Http\Requests\SelectionRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
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

    public function store(SelectionRequest $request)
    {
        $userSelection = $this->objectRepository->getObjectByName($request->selection);
        Session::flush();
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);

        $computerSelection = $this->objectRepository->getComputerSelection();
        $computer = UserType::COMPUTER;
        $computerSelection = $request->session()->put("{$computer}-selection", $computerSelection);

        return Response::json([], 200);
    }
}
