<?php 
namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameCombination;
use App\Entity\Ticket;
use App\Repository\GameRepository;
use App\Repository\GameRoundRepository;
use App\Repository\UserRepository;
use App\Service\BoundaryChecker;
use App\Service\CheckCombinationFormat;
use App\Service\DuplicateNumberChecker;
use App\Utils\StringToArrayConverter;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    private CheckCombinationFormat $checkCombinationFormat;
    private StringToArrayConverter $stringToArrayConverter;
    private DuplicateNumberChecker $duplicateNumberChecker;
    private BoundaryChecker $boundaryChecker;
    private GameRepository $gameRepository;
    private GameRoundRepository $gameRoundRepository;
    private UserRepository $userRepository;

    public function __construct(
        CheckCombinationFormat $checkCombinationFormat, 
        StringToArrayConverter $stringToArrayConverter, 
        DuplicateNumberChecker $duplicateNumberChecker,
        BoundaryChecker $boundaryChecker,
        GameRepository $gameRepository,
        GameRoundRepository $gameRoundRepository,
        UserRepository $userRepository
        )
    {
        $this->checkCombinationFormat = $checkCombinationFormat;
        $this->stringToArrayConverter = $stringToArrayConverter;
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->boundaryChecker = $boundaryChecker;
        $this->gameRepository = $gameRepository;
        $this->gameRoundRepository = $gameRoundRepository;
        $this->userRepository = $userRepository;
        //inject clock interface
    }

    #[Route(
        '/games/{id}/ticket',
        methods: ['GET']
    )]
    public function showAction(Game $game, Request $request): Response
    {
        $game = $this->gameRepository->findOneBy(["slug" => $slug]);

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
    public function postAction(string $slug, Request $request, ManagerRegistry $doctrine): Response
    {
        $gameRoundId = $request->request->get("gameRoundID");
        $inputedCombination = $request->request->get("combination");

        $entityManager = $doctrine->getManager(); //v konstruktorju tudi repositorye...

        $gameRound = $this->gameRoundRepository->findOneBy(["id" => $gameRoundId]);

        if (!$this->checkCombinationFormat->checkComboFormat($inputedCombination, $gameRound->getGame()->getHowManyNumbers())) {
            
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
        $combination = new GameCombination($this->duplicateNumberChecker, $inputedCombination);

        $email = $this->getUser()->getUserIdentifier(); //I want to retrieve id automaticlly but doesnt work
        $user = $this->userRepository->findUserByEmail($email);

        $ticket = new Ticket($gameRound, $user, $combination);
        $ticket->setScheduledAt(new DateTimeImmutable());

        $entityManager->persist($combination); //remove
        $entityManager->flush(); //remove

        $entityManager->persist($ticket);  //tickerRepository->add() uporabi
        $entityManager->flush();

        return new RedirectResponse('/');
    }
}