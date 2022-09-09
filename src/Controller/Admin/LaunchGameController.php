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

    
       if (!empty($gameRounds)) {
            $gameRounds[0]->getDateAndTime();
        }
        
        

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

        $drawer = new Drawer($this->duplicateNumberChecker);
        $gameCombination = $drawer->drawCombination($gameRound->getGame());

        $gameRound->setDrawnCombination($gameCombination->getNumbers()); //kar COmbination shran
        $gameRound->setPlayedAlready(true); //fix it

        $entityManager->persist($gameRound); //nardim preko add metode v repositoryu
        $entityManager->flush();
        
        $tickets = $ticketRepository->findBy(["gameRound" => $gameRound]); //specificna metoda npr findByGameRound

        //dd($tickets);
        
        $this->ticketToGameRoundCombinationMatcher->createTicketResult($tickets);
        
        return new RedirectResponse('/admin');
    }
}