<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;

class GuessService
{
    protected $objectRepository;

    public function __construct()
    {
        $this->objectRepository = new ObjectRepository();
    }

    public function handle(): array
    {
        $attributes = $this->getRemainingAttributes();

        $chosenAttribute = Arr::random($attributes);

        return [
            'choice' => $chosenAttribute,
            'matching' => $this->objectRepository
                ->getMatchingObjects($chosenAttribute, Session::get('user-selection'))
        ];
    }

    protected function getRemainingAttributes(): array
    {
        if (Session::get('computer-attributes')) {
            return Session::get('computer-attributes');
        }

        return $this->objectRepository->getAttributes();
    }
}
