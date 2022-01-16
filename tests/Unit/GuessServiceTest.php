<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Constants\UserType;
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

        $this->artisan('migrate:fresh', [
            '--seed' => true,
        ]);

        $this->guessService = new GuessService();
        $this->objectRepository = new ObjectRepository();
    }

    public function testComputerGuesses()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);
        $attributesFirstGuess = $this->guessService->handle('hat', UserType::COMPUTER);

        $this->assertEquals(5, $attributesFirstGuess['match-count']);

        $attributesSecondGuess = $this->guessService->handle('black hair', UserType::COMPUTER);
        $this->assertEquals(2, $attributesSecondGuess['match-count']);

        $attributesThirdGuess = $this->guessService->handle('male', UserType::COMPUTER);
        $this->assertEquals(1, $attributesThirdGuess['match-count']);
    }

    public function testUserGuesses()
    {
        $computerSelection = $this->objectRepository->getObjectByName('Benjamin');
        $computer = UserType::COMPUTER;
        Session::put("{$computer}-selection", $computerSelection);


        $attributesFirstGuess = $this->guessService->handle('ginger hair', UserType::PERSON);
        $this->assertCount(2, $attributesFirstGuess['matching']);

        $expectedData = [
            'choice' => 'ginger hair',
            'correct' => true,
            'matching' =>  [[
                'id' => 9,
                'name' => 'Benjamin',
            ], [
                'id' => 14,
                'name' => 'Henry',
            ]],
            'No' => 'No',
            'Yes' => 'Yes',
        ];

        $this->assertEquals($expectedData, $attributesFirstGuess);

        $attributesSecondGuess = $this->guessService->handle('blue eyes', UserType::PERSON);
        $this->assertCount(1, $attributesSecondGuess['matching']);
    }

    public function testRemainingAttributesDoesNotContainOpposite()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);
        $this->guessService->handle('female', UserType::COMPUTER);

        $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);

        $expectedData = [
            'mustache',
            'brown eyes',
            'brown hair',
            'wide mouth',
            'small nose',
            'white hair',
            'big nose',
            'blue eyes',
            'hat',
            'small mouth',
            'black hair',
            'blond hair',
            'glasses',
            'ginger hair',
            'beard',
            'bald',
            'green eyes',
        ];

        $this->assertEquals($expectedData, $attributes);
    }

    public function testAskSameQuestionOnlyOnce()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);

        $guessWord = 'small nose';

        $this->guessService->handle($guessWord, UserType::COMPUTER);

        $attributes = $this->objectRepository->getRemainingAttributes(UserType::COMPUTER);

        $this->assertNotContains($guessWord, $attributes);
    }
}
