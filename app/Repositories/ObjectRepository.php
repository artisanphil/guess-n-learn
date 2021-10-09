<?php

namespace App\Repositories;

use App\Constants\UserType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ObjectRepository
{
    public function getObjects(): array
    {
        $objects = file_get_contents(base_path('resources/json/characters.json'));

        return json_decode($objects, true);
    }

    public function getObjectNames(): array
    {
        $objects = $this->getObjects();

        return array_map(function ($value) {
            return $value['name'];
        }, $objects);
    }

    public function getObjectByName(string $name): array
    {
        $objects = $this->getObjects();

        return current(array_filter($objects, function ($value) use ($name) {
            return $value['name'] === $name;
        }));
    }

    public function getAttributes(array $objects, string $guesser): array
    {
        $allAttributes = array_map(function ($value) {
            return $value['attributes'];
        }, $objects);

        $allAttributesFlattened = Arr::flatten($allAttributes);
        $uniqueAttributes = array_unique($allAttributesFlattened);
        $attributes = array_values($uniqueAttributes);

        return $this->removeAlreadyAsked($attributes, $guesser);
    }

    protected function removeAlreadyAsked(array $attributes, string $guesser): array
    {
        $guessHistory = Session::get("{$guesser}-guess-history");
        $attributes = $this->removeOppositeAttributes($attributes, $guesser);

        if (!isset($guessHistory)) {
            return $attributes;
        }

        foreach ($guessHistory as $key => $value) {
            if (($key = array_search($value, $attributes)) !== false) {
                array_splice($attributes, $key, 1);
            }
        }

        return $attributes;
    }

    protected function removeOppositeAttributes(array $attributes, string $guesser): array
    {
        $guessHistory = Session::get("{$guesser}-guess-history");

        $opposites = [
            'female' => 'male',
            'male' => 'female',
        ];

        foreach ($opposites as $key => $value) {
            if (isset($guessHistory) && in_array($key, $guessHistory)) {
                if (($key = array_search($value, $attributes)) !== false) {
                    array_splice($attributes, $key, 1);
                }
            }
        }

        return $attributes;
    }

    public function getComputerSelection(): array
    {
        $objects = $this->getObjects();

        return Arr::random($objects);
    }

    public function hasAttribute(string $chosenAttribute, array $selection)
    {
        return in_array($chosenAttribute, $selection['attributes']);
    }

    public function getMatchingObjects(array $objects, string $chosenAttribute, bool $hasAttribute): array
    {
        $matchingObjects = [];

        foreach ($objects as $object) {
            $hasMatch = false;
            foreach ($object['attributes'] as $attribute) {
                if ($attribute === $chosenAttribute) {
                    $hasMatch = true;
                }
                if ($hasAttribute && $hasMatch) {
                    $matchingObjects[] = $object;
                    $hasMatch = false;
                }
            }
            if (!$hasAttribute && !$hasMatch) {
                $matchingObjects[] = $object;
            }
        }

        return $matchingObjects;
    }

    public function getRemainingAttributes(string $guesser): array
    {
        if (Session::get("remaining-{$guesser}-objects")) {
            $objects = Session::get("remaining-{$guesser}-objects");
        } else {
            $objects = $this->getObjects();
        }

        return $this->getAttributes($objects, $guesser);
    }

    public function getRemainingAttributesWithTranslations(string $guesser): array
    {
        $remainingAttributes = $this->getRemainingAttributes($guesser);
        $remainingAttributesTranslated = [];
        $learnLanguage = Session::get('learn-language', 'en');

        $i = 0;
        foreach ($remainingAttributes as $value) {
            $remainingAttributesTranslated[$i]['key'] = $value;
            $remainingAttributesTranslated[$i]['value'] = __($value, [], $learnLanguage);
            $i++;
        }

        return $remainingAttributesTranslated;
    }
}
