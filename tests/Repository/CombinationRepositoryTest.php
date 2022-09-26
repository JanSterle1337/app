<?php

namespace App\Tests\Repository;

use App\Entity\Combination;
use App\Service\DuplicateNumberChecker;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CombinationRepositoryTest extends KernelTestCase
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

    public function testInsertion(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        $duplicateNumberChecker = $container->get(DuplicateNumberChecker::class);
        
        $combination = new Combination($duplicateNumberChecker, [20, 21, 22, 33, 44]);

        $this->entityManager->persist($combination);
        $this->entityManager->flush();

        $insertedCombo = $this->entityManager
            ->getRepository(Combination::class)
            ->findOneBy(['id' => $combination]);

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame([20, 21, 22, 33, 44], $insertedCombo->getNumbers());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
