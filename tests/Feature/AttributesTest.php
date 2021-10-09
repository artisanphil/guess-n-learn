<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributesTest extends TestCase
{
    public function testRemainingAttributes()
    {
        $this->getJson('api/remaining-attributes')
            ->assertOk()
            ->assertJsonStructure([[
                'key',
                'value'
            ]]);
    }
}
