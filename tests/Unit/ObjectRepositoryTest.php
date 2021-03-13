<?php

namespace Tests\Unit;

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
            'David',
            [
                'Male',
                'Brown eyes',
                'Blonde hair',
                'Small nose',
                'Big mouth',
                'Hat'
            ]
        ];

        $this->assertEquals($expectedData, $object);
    }

    public function testGetAttributes()
    {
        $objects = $this->objectRepository->getObjects();
        $attributes = $this->objectRepository->getAttributes($objects);

        $this->assertContains('Mustache', $attributes);
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
        $hasAttribute = $this->objectRepository->hasAttribute('Hat', $object);
        $this->assertTrue($hasAttribute);

        $hasAttribute = $this->objectRepository->hasAttribute('Beard', $object);
        $this->assertFalse($hasAttribute);
    }

    public function testHasMatchingObjects()
    {
        $objects = $this->objectRepository->getObjects();
        $matchingObjects = $this->objectRepository->getMatchingObjects($objects, 'Bald', true);

        $expectedData = [
            'Liam',
            [
                'Male',
                'Glasses',
                'Brown eyes',
                'Bald',
                'White hair',
                'Small mouth',
                'Small nose',
            ]
        ];

        $notExpected = [
            'Noah',
            [
                'Male',
                'Mustache',
                'Brown eyes',
                'Brown hair',
                'Big mouth',
                'Small nose'
            ]
        ];

        $this->assertCount(4, $matchingObjects);
        $this->assertContains($expectedData, $matchingObjects);
        $this->assertNotContains($notExpected, $matchingObjects);
    }

    public function testNotHasMatchingObjects()
    {
        $objects = $this->objectRepository->getObjects();
        $matchingObjects = $this->objectRepository->getMatchingObjects($objects, 'Bald', false);

        $expectedData = [
            'Noah',
            [
                'Male',
                'Mustache',
                'Brown eyes',
                'Brown hair',
                'Big mouth',
                'Small nose'
            ]
        ];

        $notExpected = [
            'Liam',
            [
                'Male',
                'Glasses',
                'Brown eyes',
                'Bald',
                'White hair',
                'Small mouth',
                'Small nose',
            ]
        ];

        $this->assertCount(20, $matchingObjects);
        $this->assertContains($expectedData, $matchingObjects);
        $this->assertNotContains($notExpected, $matchingObjects);
    }

    public function testRemainingAttributes()
    {
        $attributes = $this->objectRepository->getRemainingAttributes();

        $this->assertCount(20, $attributes);

        $objects = $this->objectRepository->getObjects();
        $remainingObjects = $this->objectRepository->getMatchingObjects($objects, 'Bald', true);
        Session::put('remaining-user-objects', $remainingObjects);

        $attributes = $this->objectRepository->getRemainingAttributes();

        $expectedData = [
            'Male',
            'Glasses',
            'Brown eyes',
            'Bald',
            'White hair',
            'Small mouth',
            'Small nose',
            'Blonde hair',
            'Beard',
            'Big nose',
            'Blue eyes',
            'Brown hair'
        ];

        $this->assertCount(12, $attributes);
        $this->assertEquals($expectedData, $attributes);
    }
}
