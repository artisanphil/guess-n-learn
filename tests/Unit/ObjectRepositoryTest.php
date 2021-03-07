<?php

namespace Tests\Unit;

use App\Repositories\ObjectRepository;
use Tests\TestCase;

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
}
