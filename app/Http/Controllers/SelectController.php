<?php

namespace App\Http\Controllers;

use App\Constants\UserType;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\ObjectRepository;
use App\Http\Requests\SelectionRequest;
use App\Models\ObjectModel;
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
        return ObjectModel::all();
    }

    public function show(): array
    {
        $person = UserType::PERSON;

        return Session::get("{$person}-selection");
    }

    public function learnlanguage(Request $request): Response
    {
        Session::flush();
        Session::put('learn-language', $request->route('locale'));
        LogHelper::saveAction(false, 'learn-language', $request->route('locale'));

        return response(200);
    }

    public function store(SelectionRequest $request): array
    {
        $userSelection = $this->objectRepository->getObjectByName($request->selection);
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);
        LogHelper::saveAction(false, 'user-selection', $request->selection);

        $computerSelection = $this->objectRepository->getComputerSelection();
        $computer = UserType::COMPUTER;
        $request->session()->put("{$computer}-selection", $computerSelection);
        LogHelper::saveAction(true, 'computer-selection', $computerSelection['name']);

        return [];
    }
}
