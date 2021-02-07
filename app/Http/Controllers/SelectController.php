<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectionRequest;
use App\Repositories\ObjectRepository;
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
        $request->session()->put('user-selection', $userSelection);

        $computerSelection = $this->objectRepository->getComputerSelection();
        $computerSelection = $request->session()->put('computer-selection', $computerSelection);

        return response('OK', 200);
    }
}
