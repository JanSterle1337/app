<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuyTicketTest extends WebTestCase
{
    public function testBuyingTicket(): void
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findUserByEmail('test@gmail.com');
        $client->loginUser($testUser);

        

        $client->request('GET', '/games/Loto/ticket');

        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input');
        $this->assertSelectorExists('button.btn.btn-primary.m-2');

        /*$client->submitForm('Submit combination', [
            '[loto-combination]' => '1, 5, 10, 15, 20, 25, 30'
        ]);*/

       // $this->assertResponseRedirects();
        //$client->followRedirect();
    }
}
