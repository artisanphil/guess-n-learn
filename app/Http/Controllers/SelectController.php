<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectionRequest;
use App\Repositories\ObjectRepository;
use App\Repositories\Objects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class SelectController extends BaseController
{
    public function index()
    {
        $objectRepository = new ObjectRepository();

        return $objectRepository->getObjects();
    }

    public function store(SelectionRequest $request)
    {
        $request->session()->put('selection', $request->selection);

        return response('OK', 200);
    }
}
