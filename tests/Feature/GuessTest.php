<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuessTest extends TestCase
{
    public function testWrongSentenceTest()
    {
        $data = [
            'choice' => 'Glasses',
            'sentence' => 'gibberish'
        ];

        $response = $this->postJson('api/user-guess', $data)
            ->assertStatus(422);

        $this->assertEquals('The selected sentence is invalid.', $response->json('errors.sentence.0'));
    }
}
