<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuessTest extends TestCase
{
    public function testWrongSentenceTest()
    {
        $data = [
            'choice' => 'glasses',
            'sentence' => 'gibberish'
        ];

        $response = $this->postJson('api/user-guess', $data)
            ->assertStatus(422);

        $this->assertEquals('The selected sentence is invalid.', $response->json('errors.sentence.0'));
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
            'blonde hair',
            'small nose',
            'big mouth',
        ];
        Session::put("{$guesser}-guess-history", $guessHistory);

        $response = $this->get('api/computer-guess');

        $expectedData = [
            'sentence' => 'Your person is David',
            'choice' => ''
        ];

        $this->assertEquals($expectedData, $response->json());
    }
}
