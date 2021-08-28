<?php

namespace App\Http\Controllers;

use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use App\Http\Requests\SelectionRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;

class ComputerSelectController extends BaseController
{
    protected $objectRepository;

    public function __construct()
    {
        $this->objectRepository = new ObjectRepository();
    }

    public function store(SelectionRequest $request): array
    {
        $computerSelection = $this->objectRepository->getObjectByName($request->selection);
        $computer = UserType::COMPUTER;
        Session::put("{$computer}-selection", $computerSelection);

        return [];
    }
}
