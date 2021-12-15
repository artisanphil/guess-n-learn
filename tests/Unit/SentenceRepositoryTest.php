<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use App\Repositories\SentenceRepository;

class SentenceRepositoryTest extends TestCase
{
    protected $sentenceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', [
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

    public function testGetTranslatedSentenceByAttribute()
    {
        Session::put('learn-language', 'es-es');

        $sentenceRepository = new SentenceRepository();
        $sentence = $sentenceRepository->getSentenceByAttribute('mustache');

        $expectedSentence = '¿Tiene la persona un bigote?';

        $this->assertEquals($expectedSentence, $sentence);
    }
}
