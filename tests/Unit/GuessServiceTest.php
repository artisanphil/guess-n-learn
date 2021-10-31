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

    public function testComputerGuesses()
    {
        $userSelection = $this->objectRepository->getObjectByName('David');
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);
        $attributesFirstGuess = $this->guessService->handle('hat', UserType::COMPUTER);

        $this->assertEquals(5, $attributesFirstGuess['match-count']);

        $attributesSecondGuess = $this->guessService->handle('blonde hair', UserType::COMPUTER);
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
                'name' => 'Benjamin',
                'attributes' => [
                    'male',
                    'blue eyes',
                    'ginger hair',
                    'small nose',
                    'small mouth',
                ],
            ], [
                'name' => 'Henry',
                'attributes' => [
                    'male',
                    'brown eyes',
                    'ginger hair',
                    'small nose',
                    'small mouth',
                ]
            ]]
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
            'glasses',
            'brown eyes',
            'bald',
            'white hair',
            'small mouth',
            'small nose',
            'mustache',
            'green eyes',
            'brown hair',
            'big mouth',
            'big nose',
            'blue eyes',
            'black hair',
            'hat',
            'ginger hair',
            'blond hair',
            'beard'
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
