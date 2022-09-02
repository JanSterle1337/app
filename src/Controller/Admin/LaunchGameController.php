<?php
namespace App\Controller\Admin;

use App\Repository\GameRepository;
use App\Repository\GameRoundRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LaunchGameController extends AbstractController
{
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
    public function postAction(string $id,ManagerRegistry $doctrine, GameRoundRepository $gameRoundRepository, GameRepository $gameRepository): Response
    {
        $entityManager = $doctrine->getManager();

        $gameRound = $gameRoundRepository->findOneBy(["id" => $id ]);
        $game = $gameRepository->findOneBy(["id" => $gameRound->getGameID()->getId()]);

        //$roundGeneratedCombination

        dd($game);
        
        return new Response("Launched");
    }
}