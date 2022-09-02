<?php 
namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameCombination;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\TicketFormType;
use App\Repository\GameCombinationRepository;
use App\Repository\GameRepository;
use App\Repository\GameRoundRepository;
use App\Repository\UserRepository;
use App\Service\BoundaryChecker;
use App\Service\CheckCombinationFormat;
use App\Service\DuplicateNumberChecker;
use App\Utils\StringToArrayConverter;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TicketController extends AbstractController
{
    private CheckCombinationFormat $checkCombinationFormat;
    private StringToArrayConverter $stringToArrayConverter;
    private DuplicateNumberChecker $duplicateNumberChecker;
    private BoundaryChecker $boundaryChecker;

    public function __construct(
        CheckCombinationFormat $checkCombinationFormat, 
        StringToArrayConverter $stringToArrayConverter, 
        DuplicateNumberChecker $duplicateNumberChecker,
        BoundaryChecker $boundaryChecker
        )
    {
        $this->checkCombinationFormat = $checkCombinationFormat;
        $this->stringToArrayConverter = $stringToArrayConverter;
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->boundaryChecker = $boundaryChecker;
    }

    #[Route(
        '/games/{slug}/ticket',
        methods: ['GET']
    )]
    public function showAction(string $slug,Request $request, GameRepository $gameRepository, GameRoundRepository $gameRoundRepository): Response
    {
        $ticket = new Ticket();
        $game = $gameRepository->findOneBy(["slug" => $slug]);
        $ticket->setGameID($game->getId());

        $gameRounds = $gameRoundRepository->findBy([
            "gameID" => $game
        ]);

        return new Response(
            $this->render(
                'ticket/ticketForm.html.twig',
                [
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
    Request $request , 
    GameRepository $gameRepository,
    GameRoundRepository $gameRoundRepository, 
    UserRepository $userRepository,
    ManagerRegistry $doctrine
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $ticket = new Ticket();

        $gameRoundID = $request->request->get("gameRoundID");
        $gameRound = $gameRoundRepository->findOneBy(["id" => $gameRoundID]);
        $inputedCombination = $request->request->get("combination");

        $email = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findUserByEmail($email);

        if (!$this->checkCombinationFormat->checkComboFormat($inputedCombination, $gameRound->getGameID()->getHowManyNumbers())) {
            
                return new Response(
                    $this->render('error/404.html.twig')->getContent(),
                    404
                );

        }

        $inputedCombination = $this->stringToArrayConverter->converter(", "," ", $inputedCombination); 

        $combination = new GameCombination($this->duplicateNumberChecker, $inputedCombination);

        $ticket->setGameRoundID($gameRound);
        $ticket->setGameID($gameRound->getGameID()->getId());
        $ticket->setUserID($user);
        $ticket->setCreatedAt(new DateTimeImmutable());
     
        $ticket->setCombination($combination, $this->boundaryChecker);
        
        $entityManager->persist($combination);
        $entityManager->flush();

        $entityManager->persist($ticket);
        $entityManager->flush();

        return new Response('Posted');
    }

}