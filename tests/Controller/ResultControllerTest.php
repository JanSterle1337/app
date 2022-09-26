<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResultControllerTest extends WebTestCase
{
    public function testResultsWhenUserIsLogedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findUserByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/results');
        $this->assertResponseIsSuccessful();    
        $this->assertSelectorExists('table');
        $this->assertSelectorNotExists('p text-danger');
        //$this->assertSelectorTextContains('table');
    }

    public function testResultsWhenUserIsNotLoggedIn(): void 
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/results');
        $this->assertResponseRedirects('/not_found',302);
        echo $client->getResponse();
        
    }
}
