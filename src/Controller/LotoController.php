<?php 
namespace App\Controller;

use App\Entity\Combination;
use App\Repository\GameRepository;
use App\Entity\Game;
use App\Service\CheckCombinationFormat;
use App\Service\DuplicateNumberChecker;
use App\Utils\StringToArrayConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LotoController extends AbstractController 
{


    #[Route(
        '/games/{slug}', 
        methods: ['GET', 'HEAD']
        )
    ]
    public function show(string $slug, Request $request, GameRepository $gameRepository): Response
    {
       return new Response(
        $this->render(
            'games/base-game.html.twig',
            [
                'slug' => 'loto',
                'errors' => "",
                'playedCombination' => null,
                'generatedCombination' => null,
                'matchedCombination' => null,
                'success' => false
            ]
            )->getContent()
       );
    }

    #[Route(
        '/games/{slug}',
        methods: ['POST']
        ) 
    ]
    public function playAction(
        Request $request, 
        string $slug, 
        GameRepository $gameRepository, 
        CheckCombinationFormat $checkCombinationFormat,
        StringToArrayConverter $stringToArrayConverter,
        DuplicateNumberChecker $duplicateNumberChecker
        ): Response
    {
        $game = $gameRepository->findOneBy(["slug" => $slug]);
        echo "<pre>";
        var_dump($game->getHowManyNumbers()); 
        echo "</pre>";
        
        if (!$checkCombinationFormat->checkComboFormat($request->request->get("loto-combination"), $game->getHowManyNumbers())) {
            return new Response(
                $this->render(
                    'error/404.html.twig'
                )->getContent(),
                404
                );
        }

        $userData = $stringToArrayConverter->convert(", ", " ", "loto-combination", $request->request->all());

        $playedCombination = new Combination($userData, $duplicateNumberChecker);

        echo "<pre>";
        var_dump($userData);
        echo "</pre>";

        echo "Vse je okej";
        return new Response($slug);
    }
}