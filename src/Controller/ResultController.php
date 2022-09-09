<?php 

namespace App\Controller;

use App\Repository\GameCombinationRepository;
use App\Repository\GameRoundRepository;
use App\Repository\SQLRepository;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use App\Service\DuplicateNumberChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    private UserRepository $userRepository;
    private TicketRepository $ticketRepository;

    public function __construct(
        UserRepository $userRepository,
        TicketRepository $ticketRepository
        )
    {
        $this->userRepository = $userRepository;
        $this->ticketRepository = $ticketRepository;
    }

    #[Route(
        '/results',
        methods: ['GET']
    )]
    public function showAction(): Response
    {
        $user = $this->getUser();
        $email = $user->getUserIdentifier();
        dd($user);
        $user= $this->userRepository->findOneBy(["email" => $email]); //svoja metoda

        $userAndTickets = $this->ticketRepository->findAllUsersTickets($user); //fixi poi,enovanje

        if ($userAndTickets == null) {

            return new Response(
                $this->render('error/resultError.html.twig', [
                    "errors" => "You haven't participated in any events yet."
                ])->getContent()
            );
        }

        return new Response(
            $this->render('result/result.html.twig',[
                'userAndTickets' => $userAndTickets
            ])->getContent()
        );
    }
}