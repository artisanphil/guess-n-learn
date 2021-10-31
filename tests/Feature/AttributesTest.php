<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributesTest extends TestCase
{
    public function testRemainingAttributes(): void
    {
        $this->getJson('api/remaining-attributes')
            ->assertOk()
            ->assertJsonStructure([[
                'key',
                'value'
            ]]);
    }

    public function testVerifyAttributeCorrect(): void
    {
        $this->postJson('api/user-guess/verify-attribute', [
            'chosenAttribute' => 'brown hair',
            'answerAttribute' => 'Brown hair'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => true
            ]);
    }

    public function testVerifyAttributeWrong(): void
    {
        $this->postJson('api/user-guess/verify-attribute', [
            'chosenAttribute' => 'brown hair',
            'answerAttribute' => 'gibberish'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => false
            ]);
    }
}
