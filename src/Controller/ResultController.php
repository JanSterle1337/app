<?php 

namespace App\Controller;

use App\Repository\GameCombinationRepository;
use App\Repository\GameRoundRepository;
use App\Repository\ResultRepository;
use App\Repository\SQLRepository;
use App\Repository\UserRepository;
use App\Service\DuplicateNumberChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    private DuplicateNumberChecker $duplicateNumberChecker;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker)
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
    }

    #[Route(
        '/results',
        methods: ['GET']
    )]
    public function showAction(SQLRepository $sqlRepository, GameRoundRepository $gameRoundRepository, GameCombinationRepository $gameCombinationRepository): Response
    {
        $user = $this->getUser();
        $email = $user->getUserIdentifier();
        $userAndTickets = $sqlRepository->getUserTicketsForAllEvents($email);

        if ($userAndTickets == null) {

            return new Response(
                $this->render('error/resultError.html.twig', [
                    "errors" => "You haven't participated in any events yet."
                ])->getContent()
            );
        }

        foreach ($userAndTickets->getTickets() as $ticket) {

            $id = $ticket->getGameRound()->getId();
            $combinationID = $ticket->getCombination()->getId();
            $ticket->getGameRound()->setDrawnCombination($gameRoundRepository->findOneBy(["id" => $id])->getDrawnCombination());
            $ticket->getCombination()->setNumbers($gameCombinationRepository->findOneBy(["id" => $combinationID])->getNumbers(), $this->duplicateNumberChecker);

        }

        return new Response(
            $this->render('result/result.html.twig',[
                'userAndTickets' => $userAndTickets
            ])->getContent()
        );
    }
}