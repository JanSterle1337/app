<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UnauthorizedControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/not_found');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Access to this page is restricted');
        $this->assertSelectorTextContains('p', 'Please check with the site admin if you believe this is a mistake');
    }
}
