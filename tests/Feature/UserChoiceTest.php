<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Constants\UserType;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\Session;

class UserChoiceTest extends TestCase
{
    public function testGetChoice()
    {
        $selectedObjectName = 'David';
        $objectRepository = new ObjectRepository();
        $userSelection = $objectRepository->getObjectByName($selectedObjectName);
        Session::flush();
        $person = UserType::PERSON;
        Session::put("{$person}-selection", $userSelection);

        $response = $this->get('api/select')
            ->assertOk();

        $this->assertEquals($selectedObjectName, $response->json('name'));
    }
}
