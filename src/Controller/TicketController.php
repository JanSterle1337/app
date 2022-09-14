<?php 
namespace App\Controller;

use App\Entity\Game;
use App\Entity\Combination;
use App\Entity\Ticket;
use App\Repository\GameRepository;
use App\Repository\GameRoundRepository;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use App\Service\BoundaryChecker;
use App\Service\CheckCombinationFormat;
use App\Service\DuplicateNumberChecker;
use App\Utils\StringToArrayConverter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Intriro\Clock\SystemClock;

class TicketController extends AbstractController
{
    private CheckCombinationFormat $checkCombinationFormat;
    private StringToArrayConverter $stringToArrayConverter;
    private DuplicateNumberChecker $duplicateNumberChecker;
    private GameRoundRepository $gameRoundRepository;
    private UserRepository $userRepository;
    private ManagerRegistry $doctrine;
    private EntityManagerInterface $entityManager;
    private SystemClock $clock;
    private TicketRepository $ticketRepository;

    public function __construct(
        CheckCombinationFormat $checkCombinationFormat, 
        StringToArrayConverter $stringToArrayConverter, 
        DuplicateNumberChecker $duplicateNumberChecker,
        BoundaryChecker $boundaryChecker,
        GameRepository $gameRepository,
        TicketRepository $ticketRepository,
        GameRoundRepository $gameRoundRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        SystemClock $clock
        )
    {
        $this->checkCombinationFormat = $checkCombinationFormat;
        $this->stringToArrayConverter = $stringToArrayConverter;
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->boundaryChecker = $boundaryChecker;
        $this->gameRepository = $gameRepository;
        $this->gameRoundRepository = $gameRoundRepository;
        $this->ticketRepository = $ticketRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->clock = $clock;
        //inject clock interface
    }

    #[Route(
        '/games/{slug}/ticket',
        methods: ['GET']
    )]
    public function showAction(string $slug, Game $game): Response
    {
        $gameRounds = $this->gameRoundRepository->findBy([
            "game" => $game,
            "playedAlready" => false
        ]);

        $howManyRounds = count($gameRounds);

        return new Response(
            $this->render(
                'ticket/ticketForm.html.twig',
                [
                    'howManyRounds' => $howManyRounds,
                    'slug' => $slug,
                    'gameRounds' => $gameRounds,
                    'gameRules' => $game
                ]
                )->getContent()
        );
    }

    //User user v route
    #[Route(
        '/games/{slug}/ticket',
        methods: ['POST']
    )]
    public function postAction(string $slug, Request $request): Response
    {
        $gameRoundId = $request->request->get("gameRoundID");
        $inputedCombination = $request->request->get("combination");

        $gameRound = $this->gameRoundRepository->findOneById($gameRoundId);

        if (!$this->checkCombinationFormat->isCombinationValid($inputedCombination, $gameRound->getGame()->getHowManyNumbers())) {
            
            return new Response(
                $this->render(
                    'error/ticketError.html.twig',
                    [
                        "errors" => "Combination was written incorrectly",
                        "slug" => $slug
                    ]
                )->getContent()
            );
        }

        $inputedCombination = $this->stringToArrayConverter->converter(", "," ", $inputedCombination);
        $combination = new Combination($this->duplicateNumberChecker, $inputedCombination);

        $user = $this->getUser();

        $ticket = new Ticket($gameRound, $user, $combination);
        $ticket->setCreatedAt($this->clock->now());

        //dd($ticket);
        
        //$this->entityManager->persist($ticket);
        //$this->entityManager->flush($ticket);
        $this->ticketRepository->add($ticket, true);

        return new RedirectResponse('/');
    }
}