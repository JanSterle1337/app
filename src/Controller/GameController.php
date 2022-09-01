<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route(
        '/games',
        methods: ['GET'] 
        )
    ]
    public function showAction(): Response
    {
        return new Response(
            $this->render('games/base.html.twig')->getContent()
        );
    }
}