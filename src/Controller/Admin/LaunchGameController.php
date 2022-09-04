<?php
namespace App\Controller\Admin;

use App\Repository\GameRepository;
use App\Repository\GameRoundRepository;
use App\Repository\TicketRepository;
use App\Service\Drawer;
use App\Service\DuplicateNumberChecker;
use App\Service\TicketToGameRoundCombinationMatcher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LaunchGameController extends AbstractController
{
    private DuplicateNumberChecker $duplicateNumberChecker;
    private TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher;

    public function __construct(
        DuplicateNumberChecker $duplicateNumberChecker,
        TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher
        )
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->ticketToGameRoundCombinationMatcher = $ticketToGameRoundCombinationMatcher;
    }

    #[Route(
        '/admin/launch-game',
        name: '_launch-game',
        methods: ['GET']
    )]
    public function index(ManagerRegistry $doctrine,GameRoundRepository $gameRoundRepository): Response
    {
        $entityManager = $doctrine->getManager();

        $gameRounds = $gameRoundRepository->findAll();

       
        $gameRounds[0]->getDateAndTime();
        

        return $this->render('admin/launch-round.html.twig', [
            'gameRounds' => $gameRounds
        ]);
    }

    #[Route(
        '/admin/launch-game/{id}',
        name: '_launch-home-posted',
        methods: ['POST']
    )]
    public function postAction(string $id,ManagerRegistry $doctrine, TicketRepository $ticketRepository ,GameRoundRepository $gameRoundRepository, GameRepository $gameRepository)
    {
        $entityManager = $doctrine->getManager();

        $gameRound = $gameRoundRepository->findOneBy(["id" => $id ]);
        $game = $gameRepository->findOneBy(["id" => $gameRound->getGameID()->getId()]);

        $drawer = new Drawer($this->duplicateNumberChecker);
        $gameCombination = $drawer->drawCombination($game);

        $gameRound->setDrawnCombination($gameCombination->getNumbers());
        $gameRound->setPlayedAlready(true);

        $entityManager->persist($gameRound);
        $entityManager->flush();
        
        $tickets = $ticketRepository->findAllByRoundID($gameRound->getId());
        
        $this->ticketToGameRoundCombinationMatcher->createTicketResult($tickets, $entityManager);
        
        return new RedirectResponse('/admin');
    }
}