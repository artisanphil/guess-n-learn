<?php

namespace Tests\Unit;

use App\Constants\UserType;
use Tests\TestCase;
use App\Repositories\ObjectRepository;
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

        $expectedData = [
            'name' => 'David',
            'attributes' => [
                'male',
                'brown eyes',
                'blonde hair',
                'small nose',
                'big mouth',
                'hat'
            ]
        ];

        $this->assertEquals($expectedData, $object);
    }

    public function testGetAttributes()
    {
        $objects = $this->objectRepository->getObjects();
        $attributes = $this->objectRepository->getAttributes($objects, UserType::COMPUTER);

        $this->assertContains('mustache', $attributes);
        $this->assertCount(20, $attributes);
    }

    public function testGetComputerSelection()
    {
        $objects = $this->objectRepository->getObjects();
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
        $objects = $this->objectRepository->getObjects();
        $matchingObjects = $this->objectRepository->getMatchingObjects($objects, 'bald', true);

        $expectedData = [
            'name' => 'Liam',
            'attributes' => [
                'male',
                'glasses',
                'brown eyes',
                'bald',
                'white hair',
                'small mouth',
                'small nose',
            ]
        ];

        $notExpected = [
            'name' => 'Noah',
            'attributes' => [
                'male',
                'mustache',
                'brown eyes',
                'brown hair',
                'big mouth',
                'small nose'
            ]
        ];

        $this->assertCount(4, $matchingObjects);
        $this->assertContains($expectedData, $matchingObjects);
        $this->assertNotContains($notExpected, $matchingObjects);
    }

    public function testNotHasMatchingObjects()
    {
        $objects = $this->objectRepository->getObjects();
        $matchingObjects = $this->objectRepository->getMatchingObjects($objects, 'bald', false);

        $expectedData = [
            'name' => 'Noah',
            'attributes' => [
                'male',
                'mustache',
                'brown eyes',
                'brown hair',
                'big mouth',
                'small nose'
            ]
        ];

        $notExpected = [
            'name' => 'Liam',
            'attributes' => [
                'male',
                'glasses',
                'brown eyes',
                'bald',
                'white hair',
                'small mouth',
                'small nose',
            ]
        ];

        $this->assertCount(20, $matchingObjects);
        $this->assertContains($expectedData, $matchingObjects);
        $this->assertNotContains($notExpected, $matchingObjects);
    }

    public function testRemainingAttributes()
    {
        $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);

        $this->assertCount(20, $attributes);

        $objects = $this->objectRepository->getObjects();
        $remainingObjects = $this->objectRepository->getMatchingObjects($objects, 'bald', true);
        $person = UserType::PERSON;
        Session::put("remaining-{$person}-objects", $remainingObjects);

        $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);

        $expectedData = [
            'male',
            'glasses',
            'brown eyes',
            'bald',
            'white hair',
            'small mouth',
            'small nose',
            'blonde hair',
            'beard',
            'big nose',
            'blue eyes',
            'brown hair'
        ];

        $this->assertCount(12, $attributes);
        $this->assertEquals($expectedData, $attributes);
    }
}
