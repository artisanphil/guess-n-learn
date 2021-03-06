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

        $baseDir = 'resources/json/characters.json';
        print "basedir = " . $baseDir;

        $this->objectRepository = new ObjectRepository();
    }

    public function testGetObjectNames()
    {
        $objectNames = $this->objectRepository->getObjectNames();

        dump($objectNames);
    }
}
