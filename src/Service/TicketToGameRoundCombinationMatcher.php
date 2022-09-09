<?php 
namespace App\Service;

use App\Entity\Result;
use App\Repository\GameCombinationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TicketToGameRoundCombinationMatcher 
{
    private CombinationMatcher $combinationMatcher;
    private EntityManager $entityManager;

    public function __construct(CombinationMatcher $combinationMatcher, GameCombinationRepository $gameCombinationRepositoryl, EntityManagerInterface $entityManager)
    {
        $this->combinationMatcher = $combinationMatcher;
        $this->entityManager = $entityManager;
    }

    public function createTicketResult(array $tickets)
    {
        foreach($tickets as $ticket) {
            
            $matchedGameCombination = $this->combinationMatcher->createIntersectedCombination($ticket->getCombination()->getNumbers(), $ticket->getGameRound()->getDrawnCombination());

            $ticket->setMatchedNumbers($matchedGameCombination->getNumbers());

            $this->entityManager->persist($ticket); //add
            $this->entityManager->flush(); //add
        }
    }
}