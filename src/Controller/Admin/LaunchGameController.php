<?php
namespace App\Controller\Admin;

use App\Entity\GameRound;
use App\Repository\GameRepository;
use App\Repository\GameRoundRepository;
use App\Repository\TicketRepository;
use App\Service\Drawer;
use App\Service\DuplicateNumberChecker;
use App\Service\TicketToGameRoundCombinationMatcher;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LaunchGameController extends AbstractController
{
    private DuplicateNumberChecker $duplicateNumberChecker;
    private TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher;
    private GameRoundRepository $gameRoundRepository;
    private EntityManagerInterface $entityManager;
    private TicketRepository $ticketRepository;

    public function __construct(
        DuplicateNumberChecker $duplicateNumberChecker,
        TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher,
        GameRoundRepository $gameRoundRepository,
        TicketRepository $ticketRepository,
        EntityManagerInterface $entityManager
        )
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->ticketToGameRoundCombinationMatcher = $ticketToGameRoundCombinationMatcher;
        $this->gameRoundRepository = $gameRoundRepository;
        $this->entityManager = $entityManager;
        $this->ticketRepository = $ticketRepository;

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
    public function postAction(GameRound $gameRound)
    {
        $drawer = new Drawer($this->duplicateNumberChecker);

        $gameCombination = $drawer->drawCombination($gameRound->getGame());
        $gameRound->setDrawnCombination($gameCombination); //kar COmbination shran

        $this->gameRoundRepository->add($gameRound, true);
        
        $tickets = $this->ticketRepository->findByGameRound($gameRound);
        $this->ticketToGameRoundCombinationMatcher->createTicketResult($tickets);
        
        return new RedirectResponse('/admin');
    }
}