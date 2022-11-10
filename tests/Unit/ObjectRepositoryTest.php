<?php

namespace Tests\Unit;

use App\Constants\UserType;
use App\Models\ObjectModel;
use Tests\TestCase;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class ObjectRepositoryTest extends TestCase
{
    protected $objectRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->objectRepository = new ObjectRepository();
    }

    public function testGetObjectNames()
    {
        $objectNames = $this->objectRepository->getObjectNames();

        $this->assertContains('James', $objectNames);
        $this->assertCount(24, $objectNames);
    }

    public function testGetObjectByName()
    {
        $object = $this->objectRepository->getObjectByName('David');
        $actualData = Arr::only($object, ['name']);

        $expectedData = [
            'name' => 'David',
        ];

        $this->assertEquals($expectedData, $actualData);
    }

    public function testGetAttributes()
    {
        $objects = ObjectModel::all()->toArray();
        $attributes = $this->objectRepository->getAttributes($objects, UserType::COMPUTER);

        $this->assertContains('mustache', $attributes);
        $this->assertCount(21, $attributes);
    }

    public function testGetComputerSelection()
    {
        $objects = ObjectModel::all()->toArray();
        $selection = $this->objectRepository->getComputerSelection();

        $this->assertContains($selection, $objects);
    }

    public function testHasAttribute()
    {
        $object = $this->objectRepository->getObjectByName('David');
        $hasAttribute = $this->objectRepository->hasAttribute('hat', $object);
        $this->assertTrue($hasAttribute);

        $hasAttribute = $this->objectRepository->hasAttribute('beard', $object);
        $this->assertFalse($hasAttribute);
    }

    public function testHasMatchingObjects()
    {
        $objects = ObjectModel::all()->toArray();
        $matchingObjects = $this->objectRepository->getMatchingObjects($objects, 'bald', true);

        $expectedData = 'Liam';
        $notExpected = 'Noah';

        $matchingObjects = array_column($matchingObjects, 'name');
        $this->assertCount(4, $matchingObjects);
        $this->assertContains($expectedData, $matchingObjects);
        $this->assertNotContains($notExpected, $matchingObjects);
    }

    public function testNotHasMatchingObjects()
    {
        $objects = ObjectModel::all()->toArray();
        $matchingObjects = $this->objectRepository->getMatchingObjects($objects, 'bald', false);

        $expectedData = 'Noah';
        $notExpected = 'Liam';

        $matchingObjects = array_column($matchingObjects, 'name');
        $this->assertCount(20, $matchingObjects);
        $this->assertContains($expectedData, $matchingObjects);
        $this->assertNotContains($notExpected, $matchingObjects);
    }

    public function testRemainingAttributes()
    {
        $attributes = $this->objectRepository->getRemainingAttributes(UserType::PERSON);

        $this->assertCount(21, $attributes);

        $objects = ObjectModel::all()->toArray();
        $remainingObjects = $this->objectRepository->getMatchingObjects($objects, 'bald', true);
        $person = UserType::PERSON;
        Session::put("remaining-{$person}-objects", $remainingObjects);

        $attributes = $this->objectRepository->getRemainingAttributes(UserType::PERSON);

        $expectedData = [
            'male',
            'brown eyes',
            'brown hair',
            'small nose',
            'white hair',
            'big nose',
            'blue eyes',
            'small mouth',
            'blond hair',
            'glasses',
            'beard',
            'bald',
            'green eyes',

        ];

        $this->assertCount(13, $attributes);
        $this->assertEquals($expectedData, $attributes);
    }
}
