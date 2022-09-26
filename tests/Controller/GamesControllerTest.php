<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GamesControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/games');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1#loto.fs-1', 'Loto');
        $this->assertSelectorTextContains('h1#dhi-loto.fs-1', 'DhiLoto');
        $this->assertSelectorTextContains('h1#euro-jackpot.fs-1', 'EuroJackpot');
    }
}
