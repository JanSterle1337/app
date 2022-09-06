<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(
        '/', 
        name: '_home',
        methods: ['GET'],
        )
    ]
    public function showAction(): Response
    {
        return new Response(
            $this->render(
                'home-header.html.twig',
                [
                    'errors' => null
                ]
            )->getContent()
        );
    }
}