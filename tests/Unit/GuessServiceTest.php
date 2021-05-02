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

        $this->assertCount(5, $attributesFirstGuess['matching']);

        $attributesSecondGuess = $this->guessService->handle('blonde hair', UserType::COMPUTER);
        $this->assertCount(2, $attributesSecondGuess['matching']);

        $expectedData = [
            'choice' => 'blonde hair',
            'correct' => true,
            'matching' =>  [[
                'name' => 'Isabelle',
                'attributes' => [
                    'female',
                    'blonde hair',
                    'glasses',
                    'hat',
                    'small mouth',
                    'small nose',
                    'brown eyes',
                ],
            ], [
                'name' => 'David',
                'attributes' => [
                    'male',
                    'brown eyes',
                    'blonde hair',
                    'small nose',
                    'big mouth',
                    'hat',
                ]
            ]]
        ];

        $this->assertEquals($expectedData, $attributesSecondGuess);

        $attributesThirdGuess = $this->guessService->handle('male', UserType::COMPUTER);
        $this->assertCount(1, $attributesThirdGuess['matching']);
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
            'brown hair',
            'big mouth',
            'big nose',
            'blue eyes',
            'hat',
            'long hair',
            'black hair',
            'earrings',
            'blonde hair',
            'ginger hair',
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
