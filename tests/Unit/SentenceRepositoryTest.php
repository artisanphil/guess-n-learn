<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\SentenceRepository;

class SentenceRepositoryTest extends TestCase
{
    protected $sentenceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh', [
            '--seed' => true,
        ]);

        $this->sentenceRepository = new SentenceRepository();
    }

    public function testGetSentenceByAttribute()
    {
        $sentence = $this->sentenceRepository->getSentenceByAttribute('mustache');

        $expectedSentence = 'Does the person have a mustache?';

        $this->assertEquals($expectedSentence, $sentence);
    }
}
