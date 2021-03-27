<?php

namespace App\Http\Controllers;

use App\Repositories\ObjectRepository;
use Illuminate\Routing\Controller as BaseController;

class UserAttributesController extends BaseController
{
    protected $objectRepository;

    public function __construct()
    {
        $this->objectRepository = new ObjectRepository();
    }

    public function index()
    {
        return $this->objectRepository->getRemainingAttributes();
    }
}
