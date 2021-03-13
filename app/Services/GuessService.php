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

    public function handle(string $chosenAttribute): array
    {
        $hasAttribute = $this->objectRepository->hasAttribute($chosenAttribute, Session::get('user-selection'));

        return [
            'choice' => $chosenAttribute,
            'correct' => $hasAttribute,
            'matching' => $this->getRemainingMatchingObjects($chosenAttribute, $hasAttribute)
        ];
    }

    protected function getRemainingMatchingObjects(string $chosenAttribute, bool $hasAttribute): array
    {
        if (Session::get('remaining-user-objects')) {
            $objects = Session::get('remaining-user-objects');
        } else {
            $objects = $this->objectRepository->getObjects();
        }

        $remainingObjects = $this->objectRepository->getMatchingObjects($objects, $chosenAttribute, $hasAttribute);

        Session::put('remaining-user-objects', $remainingObjects);

        return $remainingObjects;
    }
}
