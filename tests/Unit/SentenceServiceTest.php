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
        $expected = [
            'attribute' => 'bald',
            'sentence' => 'Is the person bald?'
        ];
        $found = false;
        foreach ($sentences as $sentence) {
            if ($sentence == $expected) {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    public function testGapSentences()
    {
        $sentence = $this->sentenceService->handle(QuestionType::GAP);

        $this->assertEquals(['Is the person {gap}?'], $sentence);
    }

    public function testDragDropSentences()
    {
        $sentence = $this->sentenceService->handle(QuestionType::DRAGDROP);

        $this->assertContains('Is', $sentence);
        $this->assertContains('the', $sentence);
        $this->assertContains('person', $sentence);
        $this->assertContains('bald', $sentence);
    }
}
