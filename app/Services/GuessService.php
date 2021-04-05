<?php

namespace App\Services;

use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;

class GuessService
{
    protected $objectRepository;

    public function __construct()
    {
        $this->objectRepository = new ObjectRepository();
    }

    public function handle(string $chosenAttribute, string $guesser = UserType::COMPUTER): array
    {
        $userType = $guesser == UserType::COMPUTER ? UserType::PERSON : UserType::COMPUTER;
        $hasAttribute = $this->objectRepository->hasAttribute($chosenAttribute, Session::get("{$userType}-selection"));

        $guessHistory = Session::get("{$guesser}-guess-history") ?: [];
        array_push($guessHistory, $chosenAttribute);
        Session::put("{$guesser}-guess-history", $guessHistory);

        return [
            'choice' => $chosenAttribute,
            'correct' => $hasAttribute,
            'matching' => $this->getRemainingMatchingObjects($chosenAttribute, $hasAttribute, $guesser)
        ];
    }

    protected function getRemainingMatchingObjects(string $chosenAttribute, bool $hasAttribute, string $guesser): array
    {
        if (Session::get("remaining-{$guesser}-objects")) {
            $objects = Session::get("remaining-{$guesser}-objects");
        } else {
            $objects = $this->objectRepository->getObjects();
        }

        $remainingObjects = $this->objectRepository->getMatchingObjects($objects, $chosenAttribute, $hasAttribute);

        Session::put("remaining-{$guesser}-objects", $remainingObjects);

        return $remainingObjects;
    }
}
