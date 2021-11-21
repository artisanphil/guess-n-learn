<?php

namespace Tests\Feature;

use Tests\TestCase;

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
