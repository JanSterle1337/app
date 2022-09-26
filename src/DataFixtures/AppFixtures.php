<?php 
namespace App\DataFixtures;

use App\Entity\Combination;
use App\Service\DuplicateNumberChecker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private DuplicateNumberChecker $duplicateNumberChecker;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker)
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
    }

    public function load(ObjectManager $manager): void
    {
        $combination = new Combination($this->duplicateNumberChecker, [1,2,3,4,5,6,7]);
        $combination2 = new Combination($this->duplicateNumberChecker, [1,3,5,7,9,11]);
        $combination3 = new Combination($this->duplicateNumberChecker, [1,3,5,7,9,11]);

        $manager->persist($combination);
        
        $manager->persist($combination2);
        $manager->persist($combination3);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}