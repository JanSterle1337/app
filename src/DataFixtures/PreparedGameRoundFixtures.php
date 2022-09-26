<?php 
namespace App\DataFixtures;

use App\Entity\Combination;
use App\Entity\Game;
use App\Entity\GameRound;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Service\DuplicateNumberChecker;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


//Should be used with ResultFixtures
class PreparedGameRoundFixtures extends Fixture implements FixtureGroupInterface 
{
    private DuplicateNumberChecker $duplicateNumberChecker;
    private GameRepository $gameRepository;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker, GameRepository $gameRepository)
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->gameRepository = $gameRepository;
    }

    public function load(ObjectManager $manager): void 
    {
        $game = $this->gameRepository->findOneBy(["slug" => "loto"]);

        dd($game);

        $gameRound = new GameRound();
        $gameRound->setName('Wednesday Loto');
        $gameRound->setGame($game);
        $gameRound->setScheduledAt(new DateTimeImmutable());

        $manager->persist($gameRound);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['preparedGameRound'];
    }
}