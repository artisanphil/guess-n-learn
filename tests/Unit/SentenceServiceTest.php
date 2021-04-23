<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Constants\QuestionType;
use App\Services\SentenceService;

class SentenceServiceTest extends TestCase
{
    protected $sentenceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sentenceService = new SentenceService('Bald');
    }

    public function testMultipleChoiceSentences()
    {
        $sentences = $this->sentenceService->handle(QuestionType::MCHOICE);

        $this->assertCount(3, $sentences);
        $this->assertContains(['Bald' => 'Is the person bald?'], $sentences);
    }
}
