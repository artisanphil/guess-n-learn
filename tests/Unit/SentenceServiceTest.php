<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Constants\QuestionType;
use App\Services\SentenceService;
use Illuminate\Support\Facades\Session;

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
        $sentences = $this->sentenceService->handle(QuestionType::MCHOICE, 'bald');

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

    public function testTranslatedMultipleChoiceSentences()
    {
        Session::put('learn-language', 'es-es');
        $sentenceService = new SentenceService('bald');
        $sentences = $sentenceService->handle(QuestionType::MCHOICE, 'bald');

        $this->assertCount(3, $sentences);
        $expected = [
            'attribute' => 'bald',
            'sentence' => '¿Es la persona calva?'
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
        $sentence = $this->sentenceService->handle(QuestionType::GAP, 'bald');

        $this->assertEquals(['Is the person {gap}?'], $sentence);
    }

    public function testTranslatedGapSentences()
    {
        Session::put('learn-language', 'es-es');
        $sentenceService = new SentenceService('bald');
        $sentence = $sentenceService->handle(QuestionType::GAP, 'calva');

        $this->assertEquals(['¿Es la persona {gap}?'], $sentence);
    }

    public function testDragDropSentences()
    {
        $sentence = $this->sentenceService->handle(QuestionType::DRAGDROP, 'bald');

        $this->assertContains('Is', $sentence);
        $this->assertContains('the', $sentence);
        $this->assertContains('person', $sentence);
        $this->assertContains('bald', $sentence);
    }

    public function testTranslatedDragDropSentences()
    {
        Session::put('learn-language', 'es-es');
        $sentenceService = new SentenceService('bald');
        $sentence = $sentenceService->handle(QuestionType::DRAGDROP, 'bald');

        $this->assertContains('¿Es', $sentence);
        $this->assertContains('la', $sentence);
        $this->assertContains('persona', $sentence);
        $this->assertContains('calva', $sentence);
    }
}
