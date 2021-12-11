<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Models\ObjectModel;
use Illuminate\Support\Arr;
use App\Models\ObjectAttribute;
use Illuminate\Support\Facades\Session;

class ObjectRepository
{
    public function getObjectAttributes(int $objectId): array
    {
        return ObjectModel::where('id', $objectId)
            ->first()
            ->attributes()
            ->pluck('value')
            ->toArray();
    }

    public function getObjectNames(): array
    {
        return ObjectModel::all()
            ->pluck('name')
            ->toArray();
    }

    public function getObjectByName(string $name): array
    {
        return ObjectModel::where('name', $name)
            ->first()
            ->toArray();
    }

    public function getAttributes(array $objects, string $guesser): array
    {
        $attributes = $this->getAllAttributes($objects);

        return $this->removeAlreadyAsked($attributes, $guesser);
    }

    public function getAllAttributes(array $objects): array
    {
        $objectIds = Arr::pluck($objects, 'id');
        $objectsAttributes = ObjectAttribute::whereIn('object_id', $objectIds)
            ->get()
            ->pluck('attribute_id');

        return Attribute::whereIn('id', $objectsAttributes)
            ->get()
            ->pluck('value')
            ->toArray();
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
        return ObjectModel::inRandomOrder()
            ->first()
            ->toArray();
    }

    public function hasAttribute(string $chosenAttribute, array $selection)
    {
        $selectionAttributes = $this->getObjectAttributes($selection['id']);

        return in_array($chosenAttribute, $selectionAttributes);
    }

    public function getMatchingObjects(array $objects, string $chosenAttribute, bool $hasAttribute): array
    {
        $matchingObjects = [];

        foreach ($objects as $object) {
            $attributes = $this->getObjectAttributes($object['id']);
            $hasMatch = false;
            foreach ($attributes as $attribute) {
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
            $objects = ObjectModel::all()->toArray();
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
