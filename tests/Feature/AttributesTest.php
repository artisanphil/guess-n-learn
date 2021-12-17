<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Constants\QuestionType;
use Illuminate\Support\Facades\Session;

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

    public function testTranslatedAttributes(): void
    {
        Session::put('learn-language', 'es-es');

        $response = $this->getJson('api/remaining-attributes')
            ->assertOk();

        $expectedData = [
            'key' => 'bald',
            'value' => 'calva'
        ];

        $this->assertContains($expectedData, $response->json());
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

    public function testVerifyAttributeTranslationCorrect(): void
    {
        Session::put('learn-language', 'es-es');

        $this->postJson('api/user-guess/verify-attribute', [
            'type' => QuestionType::GAP,
            'chosenAttribute' => 'hat',
            'answerAttribute' => 'sombrero'
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

    public function testSimilarTranslatedAttributeCorrect(): void
    {
        Session::put('learn-language', 'es-es');

        $this->postJson('api/user-guess/verify-attribute', [
            'type' => QuestionType::GAP,
            'chosenAttribute' => 'white hair',
            'answerAttribute' => 'pelo blanco'
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
