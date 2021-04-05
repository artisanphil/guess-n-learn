<?php

namespace Tests\Unit;

use App\Constants\UserType;
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
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);
        $attributesFirstGuess = $this->guessService->handle('Hat', UserType::COMPUTER);

        $this->assertCount(5, $attributesFirstGuess['matching']);

        $attributesSecondGuess = $this->guessService->handle('Blonde hair', UserType::COMPUTER);
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

        $attributesThirdGuess = $this->guessService->handle('Male', UserType::COMPUTER);
        $this->assertCount(1, $attributesThirdGuess['matching']);
    }

    public function testRemainingAttributesDoesNotContainOpposite()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);
        $this->guessService->handle('Female', UserType::COMPUTER);

        $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);

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
            'Hat',
            'Long hair',
            'Black hair',
            'Earrings',
            'Blonde hair',
            'Ginger hair',
            'Beard'
        ];

        $this->assertEquals($expectedData, $attributes);
    }

    public function testAskSameQuestionOnlyOnce()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);

        $guessWord = 'Small nose';

        $this->guessService->handle($guessWord, UserType::COMPUTER);

        $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);

        $this->assertNotContains($guessWord, $attributes);
    }
}
