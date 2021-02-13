<?php

namespace App\Http\Controllers;

use App\Services\GuessService;
use Illuminate\Routing\Controller as BaseController;

class GuessController extends BaseController
{
    protected $guessService;

    public function __construct()
    {
        $this->guessService = new GuessService();
    }

    public function index()
    {
        return $this->guessService->handle();
    }
}
