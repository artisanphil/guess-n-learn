<?php

namespace Tests\Unit;

use App\Constants\UserType;
use Tests\TestCase;
use App\Repositories\SentenceRepository;
use Illuminate\Support\Facades\Session;

class SentenceRepositoryTest extends TestCase
{
    protected $sentenceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sentenceRepository = new SentenceRepository();
    }

    public function testGetSentenceByAttribute()
    {
        $sentence = $this->sentenceRepository->getSentenceByAttribute('Mustache');

        $expectedSentence = 'Does the person have a mustache?';

        $this->assertEquals($expectedSentence, $sentence);
    }
}
