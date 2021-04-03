<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GuessService;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;

class GuessServiceTest extends TestCase
{
    protected $guessService;
    protected $objectRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guessService = new GuessService();
        $this->objectRepository = new ObjectRepository();
    }

    public function testSuccess()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        Session::put('user-selection', $userSelection);
        $attributesFirstGuess = $this->guessService->handle('Hat');

        $this->assertCount(5, $attributesFirstGuess['matching']);

        $attributesSecondGuess = $this->guessService->handle('Blonde hair');
        $this->assertCount(2, $attributesSecondGuess['matching']);

        $expectedData = [
            'choice' => 'Blonde hair',
            'correct' => true,
            'matching' =>  [[
                'Isabelle',
                [
                    'Female',
                    'Blonde hair',
                    'Glasses',
                    'Hat',
                    'Small mouth',
                    'Small nose',
                    'Brown eyes',
                ],
            ], [
                'David',
                [
                    'Male',
                    'Brown eyes',
                    'Blonde hair',
                    'Small nose',
                    'Big mouth',
                    'Hat',
                ]
            ]]
        ];

        $this->assertEquals($expectedData, $attributesSecondGuess);

        $attributesThirdGuess = $this->guessService->handle('Male');
        $this->assertCount(1, $attributesThirdGuess['matching']);
    }

    public function testRemainingAttributesDoesNotContainOpposite()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        Session::put('user-selection', $userSelection);
        $this->guessService->handle('Female');

        $attributes = $this->objectRepository->getRemainingAttributes();

        $expectedData = [
            'Glasses',
            'Brown eyes',
            'Bald',
            'White hair',
            'Small mouth',
            'Small nose',
            'Mustache',
            'Brown hair',
            'Big mouth',
            'Big nose',
            'Blue eyes',
            'Black hair',
            'Hat',
            'Ginger hair',
            'Blonde hair',
            'Beard',
        ];

        $this->assertEquals($expectedData, $attributes);
    }

    public function testAskSameQuestionOnlyOnce()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        Session::put('user-selection', $userSelection);

        $guessWord = 'Small nose';

        $this->guessService->handle($guessWord);

        $attributes = $this->objectRepository->getRemainingAttributes();

        $this->assertNotContains($guessWord, $attributes);
    }
}
