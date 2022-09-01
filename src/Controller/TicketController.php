<?php 
namespace App\Controller;

use App\Entity\Game;
use App\Entity\Ticket;
use App\Form\TicketFormType;
use App\Repository\GameRepository;
use App\Service\CheckCombinationFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    private CheckCombinationFormat $checkCombinationFormat;

    public function __construct(CheckCombinationFormat $checkCombinationFormat)
    {
        $this->checkCombinationFormat = $checkCombinationFormat;
    }

    #[Route(
        '/games/{slug}/ticket',
        methods: ['GET', 'POST']
    )]
    public function showAction(string $slug,Request $request ,GameRepository $gameRepository): Response
    {
        $ticket = new Ticket();
        $game = $gameRepository->findOneBy(["slug" => $slug]);
        $ticket->setGameID($game->getId());

        $form = $this->createForm(TicketFormType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dd($form->getData());
            return $this->redirectToRoute('_home');
        }

        return $this->renderForm('ticket/ticketForm.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(
        '/games/{slug}/ticket',
        methods: ['POST']
    )]
    public function postAction(string $slug, GameRepository $gameRepository): Response
    {
        return new Response('Posted');
    }

}