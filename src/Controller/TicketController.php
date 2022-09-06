<?php 
namespace App\Controller;


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
    }

    #[Route(
        '/games/{slug}/ticket',
        methods: ['GET']
    )]
    public function showAction(string $slug, Request $request): Response
    {
        $ticket = new Ticket();
        $game = $this->gameRepository->findOneBy(["slug" => $slug]);
        $ticket->setGameID($game->getId());

        $gameRounds = $this->gameRoundRepository->findBy([
            "gameID" => $game,
            "playedAlready" => false
        ]);
        
        $howManyRounds = count($gameRounds);

        return new Response(
            $this->render(
                'ticket/ticketForm.html.twig',
                [
                    'howManyRounds' => $howManyRounds,
                    'slug' => $slug,
                    'gameRounds' => $gameRounds
                ]
                )->getContent()
        );
    }

    #[Route(
        '/games/{slug}/ticket',
        methods: ['POST']
    )]
    public function postAction(
    string $slug, 
    Request $request, 
    ManagerRegistry $doctrine
    ): Response
    {
        

        $entityManager = $doctrine->getManager();
        $ticket = new Ticket();

        $gameRoundID = $request->request->get("gameRoundID");
        $gameRound = $this->gameRoundRepository->findOneBy(["id" => $gameRoundID]);
        $inputedCombination = $request->request->get("combination");

        $email = $this->getUser()->getUserIdentifier();
        $user = $this->userRepository->findUserByEmail($email);

        if (!$this->checkCombinationFormat->checkComboFormat($inputedCombination, $gameRound->getGameID()->getHowManyNumbers())) {
            
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

        $ticket->setGameRound($gameRound);
        $ticket->setGameID($gameRound->getGameID()->getId());
        $ticket->setUserID($user);
        $ticket->setCreatedAt(new DateTimeImmutable());
        $ticket->setCombination($combination, $this->boundaryChecker);
        
        $entityManager->persist($combination);
        $entityManager->flush();

        $entityManager->persist($ticket);
        $entityManager->flush();

        return new RedirectResponse('/');
    }

}