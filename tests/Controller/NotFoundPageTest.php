<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotFoundPageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/not_found');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.text-center', 'Access to this page is restricted');
        $this->assertSelectorTextContains('a.text-center','Home');

        $link = $crawler->selectLink('Home')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
    }
}
