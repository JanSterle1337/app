<?php 
namespace App\Service;

use App\Entity\Result;
use App\Repository\GameCombinationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TicketToGameRoundCombinationMatcher 
{
    private CombinationMatcher $combinationMatcher;
    private GameCombinationRepository $gameCombinationRepository;

    public function __construct(CombinationMatcher $combinationMatcher, GameCombinationRepository $gameCombinationRepository)
    {
        $this->combinationMatcher = $combinationMatcher;
        $this->gameCombinationRepository = $gameCombinationRepository;
    }

    public function createTicketResult(array $tickets, EntityManager $entityManager)
    {
        foreach($tickets as $ticket) {
            
            $matchedGameCombination = $this->combinationMatcher->createIntersectedCombination($ticket->getCombination()->getNumbers(), $ticket->getGameRoundId()->getDrawnCombination());

            $result = new Result();
            $result->setMatchedCombination($matchedGameCombination->getNumbers());
            $result->setTicketID($ticket);
            $result->setGameRoundID($ticket->getGameRoundId());
            
            $entityManager->persist($result);
            $entityManager->flush();
     
        }
    }
}