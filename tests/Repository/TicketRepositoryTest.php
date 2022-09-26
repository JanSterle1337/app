<?php

namespace App\Tests\Repository;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TicketRepositoryTest extends KernelTestCase
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

    public function testOptimalFindAllUsersTickets(): void
    {
        $kernel = self::bootKernel();

        $tickets = $this->entityManager
            ->getRepository(Ticket::class)
            ->findAllUsersTickets(3)
            ;

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame(3, $tickets[0]->getId());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testNotFoundFindAllUsersTickets(): void 
    {
        $kernel = self::bootKernel();

        $tickets = $this->entityManager
            ->getRepository(Ticket::class)
            ->findAllUsersTickets(1) //a query where no user is found
            ;

        //dd($tickets);

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame([], $tickets);
    }

    public function testOptimalFindByGameRound(): void
    {
        $kernel = self::bootKernel();

        $tickets = $this->entityManager
            ->getRepository(Ticket::class)
            ->findByGameRound(4)
            ;

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame(3, $tickets[0]->getUser()->getId());
    }
}
