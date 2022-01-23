<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Constants\UserType;
use App\Models\ObjectModel;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;

class GuessTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', [
            '--seed' => true,
        ]);
    }

    public function testComputerGuess()
    {
        $this->getJson('api/computer-guess')
            ->assertOk()
            ->assertJsonStructure([
                'sentence',
                'choice'
            ]);
    }

    public function testUserObjectGuess()
    {
        $objectSelectedByComputer = 'David';
        Session::flush();
        $objectRepository = new ObjectRepository();
        $computerSelection = $objectRepository->getObjectByName($objectSelectedByComputer);
        $computer = UserType::COMPUTER;
        Session::put("{$computer}-selection", $computerSelection);

        $this->postJson('api/user-guess/object', [
            'name' => $objectSelectedByComputer
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => true
            ]);

        $response = $this->postJson('api/user-guess/object', [
            'name' => 'Peter'
        ])
            ->assertOk()
            ->assertExactJson([
                'correct' => false
            ]);
    }

    public function testComputerFinalGuess()
    {
        $objectRepository = new ObjectRepository();
        $userSelection = $objectRepository->getObjectByName('David');
        $guesser = UserType::COMPUTER;
        Session::put("remaining-{$guesser}-objects", [$userSelection]);
        $guessHistory = [
            'male',
            'glasses',
            'brown eyes',
            'black hair',
            'small nose',
            'wide mouth',
        ];
        Session::put("{$guesser}-guess-history", $guessHistory);

        $response = $this->get('api/computer-guess');

        $expectedData = [
            'sentence' => 'Your person is David',
            'choice' => '',
            'No' => 'No',
            'Yes' => 'Yes'
        ];

        $this->assertEquals($expectedData, $response->json());
    }

    public function testRemainingObjectsEndpoint()
    {
        $objects = ObjectModel::all()->toArray();
        $objectRepository = new ObjectRepository();
        $remainingObjects = $objectRepository->getMatchingObjects($objects, 'bald', true);
        $person = UserType::PERSON;
        Session::put("remaining-{$person}-objects", $remainingObjects);

        $response = $this->get('api/remaining-objects');
        $expectedData = ['Liam', 'Lucas', 'Ethan', 'Jack'];

        $this->assertEquals($expectedData, $response->json());
    }
}
