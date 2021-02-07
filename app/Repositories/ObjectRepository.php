<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

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

    public function getComputerSelection(): array
    {
        $objects = $this->getObjects();

        return Arr::random($objects);
    }
}
