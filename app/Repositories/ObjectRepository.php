<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
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
            return $value[0];
        }, $objects);
    }

    public function getObjectByName(string $name): array
    {
        $objects = $this->getObjects();

        return current(array_filter($objects, function ($value) use ($name) {
            return $value[0] == $name;
        }));
    }

    public function getAttributes($objects): array
    {
        $allAttributes = array_map(function ($value) {
            return $value[1];
        }, $objects);

        $allAttributesFlattened = Arr::flatten($allAttributes);
        $uniqueAttributes = array_unique($allAttributesFlattened);
        $attributes = array_values($uniqueAttributes);

        return $this->removeAlreadyAsked($attributes);
    }

    protected function removeAlreadyAsked(array $attributes): array
    {
        $guessHistory = Session::get('computer-guess-history');
        $attributes = $this->removeOppositeAttributes($attributes);

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

    protected function removeOppositeAttributes(array $attributes): array
    {
        $guessHistory = Session::get('computer-guess-history');

        $opposites = [
            'Female' => 'Male',
            'Male' => 'Female'
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
        return in_array($chosenAttribute, $selection[1]);
    }

    public function getMatchingObjects(array $objects, string $chosenAttribute, bool $hasAttribute): array
    {
        $matchingObjects = [];

        foreach ($objects as $object) {
            $hasMatch = false;
            foreach ($object[1] as $attribute) {
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

    public function getRemainingAttributes(): array
    {
        if (Session::get('remaining-user-objects')) {
            $objects = Session::get('remaining-user-objects');
        } else {
            $objects = $this->getObjects();
        }

        return $this->getAttributes($objects);
    }
}
