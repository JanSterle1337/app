<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager()
            ;
    }

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findUserByEmail('test@gmail.com')
            ;
        
        
        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame('test@gmail.com', $user->getEmail());
        $this->assertSame(3, $user->getId());
    }
}
