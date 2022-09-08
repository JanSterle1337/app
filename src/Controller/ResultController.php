<?php 

namespace App\Controller;

use App\Repository\GameCombinationRepository;
use App\Repository\GameRoundRepository;
use App\Repository\SQLRepository;
use App\Service\DuplicateNumberChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    private DuplicateNumberChecker $duplicateNumberChecker;
    private GameRoundRepository $gameRoundRepository;
    private GameCombinationRepository $gameCombinationRepository;

    public function __construct(
        DuplicateNumberChecker $duplicateNumberChecker, 
        GameRoundRepository $gameRoundRepository, 
        GameCombinationRepository $gameCombinationRepository,
        )
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->gameRoundRepository = $gameRoundRepository;
        $this->gameCombinationRepository = $gameCombinationRepository;
    }

    #[Route(
        '/results',
        methods: ['GET']
    )]
    public function showAction(SQLRepository $sqlRepository): Response
    {
        $user = $this->getUser();
        $email = $user->getUserIdentifier();
        $userAndTickets = $sqlRepository->getUserTicketsForAllEvents($email); //finds all user tickets

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
            $ticket->getGameRound()->setDrawnCombination($this->gameRoundRepository->findOneBy(["id" => $id])->getDrawnCombination());
            $ticket->getCombination()->setNumbers($this->gameCombinationRepository->findOneBy(["id" => $combinationID])->getNumbers(), $this->duplicateNumberChecker); //I dont want to set the combination with setter
            //dd($ticket);
        }

        return new Response(
            $this->render('result/result.html.twig',[
                'userAndTickets' => $userAndTickets
            ])->getContent()
        );
    }
}