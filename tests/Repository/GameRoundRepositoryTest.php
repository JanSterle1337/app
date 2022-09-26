<?php

namespace App\Tests\Repository;

use App\Entity\GameRound;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameRoundRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager()
            ;
    }

    public function testOptimalFindOneById(): void
    {
        $kernel = self::bootKernel();

        $gameRound = $this->entityManager
            ->getRepository(GameRound::class)
            ->findOneById(4)
            ;

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame(15, $gameRound->getDrawnCombination()->getId());
        $this->assertSame(true, $gameRound->isPlayedAlready());
        $this->assertSame('Wednesday Honeymoon #1', $gameRound->getName());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testNullFindOneById(): void 
    {
        $kernel = self::bootKernel();

        $gameRound = $this->entityManager
            ->getRepository(GameRound::class)
            ->findOneById(99)
            ;

        $this->assertSame('test', $kernel->getEnvironment());   
        $this->assertNull($gameRound);
    }

    public function testOptimalFindByIsPlayedYet(): void 
    {
        $kernel = self::bootKernel();

        $playedYet = true;

        $gameRounds = $this->entityManager
            ->getRepository(GameRound::class)
            ->findByIsPlayedYet($playedYet)
            ;


        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame($playedYet, $gameRounds[0]->isPlayedAlready());
    }

    public function testOptimalFindByIsPlayedYetFalse(): void 
    {
        $kernel = self::bootKernel();

        $playedYet = false;

        $gameRounds = $this->entityManager
            ->getRepository(GameRound::class)
            ->findByIsPlayedYet($playedYet)
            ;


        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame($playedYet, $gameRounds[0]->isPlayedAlready());

    }

    public function testOptimalfindNotPlayedYet()
    {
        $kernel = self::bootKernel();

        $gameRounds = $this->entityManager
            ->getRepository(GameRound::class)
            ->findNotPlayedYet()
            ;

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertFalse( $gameRounds[0]->isPlayedAlready());
    }

    public function testOptimalfindNotPlayedYetBySlug(): void 
    {
        $kernel = self::bootKernel();

        $gameRound = $this->entityManager
            ->getRepository(GameRound::class)
            ->findNotPlayedYetBySlug('Wednesday Loto')
            ;

        $this->assertSame('test', $kernel->getEnvironment());   
        $this->assertSame('Wednesday Loto', $gameRound->getName());
    }

    public function testNullfindNotPlayedYetBySlug(): void 
    {
        $kernel = self::bootKernel();

        

        $gameRound = $this->entityManager
            ->getRepository(GameRound::class)
            ->findNotPlayedYetBySlug('Blablabla')
            ;
            
        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertNull($gameRound);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

}
