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

    public function handle(string $chosenAttribute, string $guesser = 'computer'): array
    {
        $who = $guesser == 'computer' ? 'user' : 'computer';
        $hasAttribute = $this->objectRepository->hasAttribute($chosenAttribute, Session::get("{$who}-selection"));

        $guessHistory = Session::get("{$guesser}-guess-history") ?: [];
        array_push($guessHistory, $chosenAttribute);
        Session::put("{$guesser}-guess-history", $guessHistory);

        return [
            'choice' => $chosenAttribute,
            'correct' => $hasAttribute,
            'matching' => $this->getRemainingMatchingObjects($chosenAttribute, $hasAttribute, $who)
        ];
    }

    protected function getRemainingMatchingObjects(string $chosenAttribute, bool $hasAttribute, string $who): array
    {
        if (Session::get("remaining-{$who}-objects")) {
            $objects = Session::get("remaining-{$who}-objects");
        } else {
            $objects = $this->objectRepository->getObjects();
        }

        $remainingObjects = $this->objectRepository->getMatchingObjects($objects, $chosenAttribute, $hasAttribute);

        Session::put("remaining-{$who}-objects", $remainingObjects);

        return $remainingObjects;
    }
}
