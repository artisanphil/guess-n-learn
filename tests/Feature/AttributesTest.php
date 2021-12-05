<?php

namespace Tests\Feature;

use App\Constants\QuestionType;
use Tests\TestCase;

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
            'type' => QuestionType::GAP,
            'chosenAttribute' => 'brown hair',
            'answerAttribute' => 'Brown hair'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => true
            ]);
    }

    public function testSimilarAttributeCorrect(): void
    {
        $this->postJson('api/user-guess/verify-attribute', [
            'type' => QuestionType::GAP,
            'chosenAttribute' => 'blond hair',
            'answerAttribute' => 'blonde hair'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => true
            ]);
    }

    public function testVerifyAttributeWrong(): void
    {
        $this->postJson('api/user-guess/verify-attribute', [
            'type' => QuestionType::GAP,
            'chosenAttribute' => 'brown hair',
            'answerAttribute' => 'black hair'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => false
            ]);
    }
}
