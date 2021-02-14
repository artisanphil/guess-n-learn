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
        $hasAttribute = $this->objectRepository->hasAttribute($chosenAttribute, Session::get('user-selection'));

        return [
            'choice' => $chosenAttribute,
            'correct' => $hasAttribute,
            'matching' => $this->objectRepository
                ->getMatchingObjects($chosenAttribute, $hasAttribute)
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
