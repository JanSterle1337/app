<?php 
namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    #[Route(
        '/results',
        methods: ['GET']
    )]
    public function showAction(): Response
    {
        $user = $this->getUser();
        $userTickets = $this->ticketRepository->findAllUsersTickets($user);
        //dd($userTickets);

        return new Response(
            $this->render('result/result.html.twig',[
                'userTickets' => $userTickets
            ])->getContent()
        );
    }
}