<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnauthorizedController extends AbstractController
{
    #[Route('/not_found', name: 'unauthorized')]
    public function showAction(Request $request): Response
    {
        return new Response($this->render('error/403.html.twig')->getContent());
    }
}