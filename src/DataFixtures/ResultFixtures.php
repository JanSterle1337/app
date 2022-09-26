<?php 
namespace App\DataFixtures;

use App\Entity\Combination;
use App\Entity\Game;
use App\Entity\GameRound;
use App\Entity\Ticket;
use App\Entity\User;
use App\Service\DuplicateNumberChecker;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResultFixtures extends Fixture implements FixtureGroupInterface 
{
    private DuplicateNumberChecker $duplicateNumberChecker;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@gmail.com');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, 'test12345')
        );
        $user->setRoles([]);
        $manager->persist($user);
        $manager->flush();

        $loto = new Game();
        $loto->setSlug('loto');
        $loto->setMinimumNumber(1);
        $loto->setMaximumNumber(39);
        $loto->setHowManyNumbers(7);

        $manager->persist($loto);
        $manager->flush();

        $gameRound = new GameRound();
        $gameRound->setName('Wednesday Honeymoon #1');
        $gameRound->setGame($loto);
        $gameRound->setScheduledAt(new DateTimeImmutable());

        $manager->persist($gameRound);
        $manager->flush();

        $userCombination = new Combination($this->duplicateNumberChecker, [2, 11, 32, 33, 4, 5, 6]);
        $ticket = new Ticket($gameRound,$user,$userCombination);
        $ticket->setCreatedAt(new DateTimeImmutable("now"));

        $manager->persist($ticket);
        $manager->flush();

        $generatedCombination = new Combination($this->duplicateNumberChecker, [2, 10, 17, 30, 32, 33, 39]);
        $gameRound->setDrawnCombination($generatedCombination);

        $manager->persist($ticket);
        $manager->flush();

        $gameRound = new GameRound();
        $gameRound->setName('Wednesday Loto');
        $gameRound->setGame($loto);
        $gameRound->setScheduledAt(new DateTimeImmutable());

        $manager->persist($gameRound);
        $manager->flush();

    }

    public static function getGroups(): array
    {
        return ['result'];
    }
}