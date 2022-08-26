<?php 
namespace App\Controller;

use App\Entity\Combination;
use App\Repository\GameRepository;
use App\Entity\Lot;
use App\Entity\Game;
use App\Entity\GameTicket;
use App\Entity\PlayingGame;
use App\Service\BoundaryChecker;
use App\Service\CheckCombinationFormat;
use App\Service\CombinationGenerator;
use App\Service\CombinationMatcher;
use App\Service\DuplicateNumberChecker;
use App\Utils\StringToArrayConverter;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LotoController extends AbstractController 
{


    #[Route(
        '/games/{slug}', 
        methods: ['GET', 'HEAD']
        )
    ]
    public function show(string $slug, Request $request, GameRepository $gameRepository): Response
    {
       return new Response(
        $this->render(
            'games/base-game.html.twig',
            [
                'slug' => 'loto',
                'errors' => "",
                'playedCombination' => null,
                'generatedCombination' => null,
                'matchedCombination' => null,
                'success' => false
            ]
            )->getContent()
       );
    }

    #[Route(
        '/games/{slug}',
        methods: ['POST']
        ) 
    ]
    public function playAction(
        Request $request, 
        string $slug, 
        GameRepository $gameRepository, 
        CheckCombinationFormat $checkCombinationFormat,
        StringToArrayConverter $stringToArrayConverter,
        DuplicateNumberChecker $duplicateNumberChecker,
        BoundaryChecker $boundaryChecker,
        CombinationGenerator $combinationGenerator,
        CombinationMatcher $combinationMatcher,
        ManagerRegistry $doctrine
        ): Response
    {
        $entityManager = $doctrine->getManager();
        $game = $gameRepository->findOneBy(["slug" => $slug]);
        echo "<pre>";
        var_dump($game->getHowManyNumbers()); 
        echo "</pre>";
        
        if (!$checkCombinationFormat->checkComboFormat($request->request->get("loto-combination"), $game->getHowManyNumbers())) {
            return new Response(
                $this->render(
                    'error/404.html.twig'
                )->getContent(),
                404
                );
        }

        $userData = $stringToArrayConverter->convert(", ", " ", "loto-combination", $request->request->all());

        $playedCombination = new Combination($userData, $duplicateNumberChecker);

        $userTicket = new GameTicket($playedCombination, $game, $boundaryChecker);

        $gameLot = new Lot($combinationGenerator, $boundaryChecker, $duplicateNumberChecker, $game);

        $matchedCombination = $combinationMatcher->createIntersectedCombination($userTicket->getCombination(), $gameLot->getCombination());

        $playedGame = new PlayingGame();
        $playedGame->setGame($game);
        $playedGame->setCombinations([
            'playedCombination' => $userTicket->getCombination()->getNumbers(),
            'generatedCombination' => $gameLot->getCombination()->getNumbers(),
            'matchedCombination' => $matchedCombination->getNumbers()
        ]);
        $playedGame->setPlayedAt(new DateTimeImmutable());

        $entityManager->persist($playedGame);
        $entityManager->flush();

        return new Response(
            $this->render(
                'games/base-game.html.twig',
                [
                    'slug' => 'loto',
                    'errors' => "",
                    'playedCombination' => $userTicket->getCombination(),
                    'generatedCombination' => $gameLot->getCombination(),
                    'matchedCombination' => $matchedCombination,
                    'success' => false
                ]
            )->getContent()
        );
    }
}