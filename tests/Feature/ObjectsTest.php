<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ObjectsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', [
            '--seed' => true,
        ]);
    }

    public function testRemainingObjects()
    {
        $remainingObjects = [[
            'id' => 9,
            'name' => 'Benjamin'
        ], [
            'id' => 14,
            'name' => 'Henry'
        ]];

        Session::put("remaining-person-objects", $remainingObjects);

        $response = $this->getJson('api/objects')
            ->assertOk();

        $this->assertCount(24, $response->json()['objects']);
        $this->assertContains([
            'id' => 14,
            'name' => 'Henry',
            'active' => true
        ], $response->json()['objects']);

        $this->assertContains([
            'id' => 1,
            'name' => 'Liam',
            'active' => false
        ], $response->json()['objects']);
    }
}
