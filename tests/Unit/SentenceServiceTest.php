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

        $this->sentenceService = new SentenceService('bald');
    }

    public function testMultipleChoiceSentences()
    {
        $sentences = $this->sentenceService->handle(QuestionType::MCHOICE);

        $this->assertCount(3, $sentences);
        $this->assertContains(['bald' => 'Is the person bald?'], $sentences);
    }

    public function testGapSentences()
    {
        $sentence = $this->sentenceService->handle(QuestionType::GAP);

        $this->assertEquals(['Is the person {gap}?'], $sentence);
    }
}
