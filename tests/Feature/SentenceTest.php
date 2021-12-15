<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class SentenceTest extends TestCase
{
    public function testVerifySentenceCorrect(): void
    {
        $this->postJson('api/user-guess/verify-sentence', [
            'chosenAttribute' => 'bald',
            'answerSentence' => 'Is the person bald?'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => true
            ]);
    }

    public function testVerifyTranslatedSentenceCorrect(): void
    {
        Session::put('learn-language', 'es-es');
        $this->postJson('api/user-guess/verify-sentence', [
            'chosenAttribute' => 'bald',
            'answerSentence' => '¿Es la persona calva?'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => true
            ]);
    }

    public function testVerifySentenceWrong(): void
    {
        $this->postJson('api/user-guess/verify-sentence', [
            'chosenAttribute' => 'bald',
            'answerSentence' => 'Does the person bald?'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => false
            ]);
    }
}
