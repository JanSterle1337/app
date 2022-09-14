<?php 
namespace App\Service;

use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TicketToGameRoundCombinationMatcher 
{
    private CombinationMatcher $combinationMatcher;
    private EntityManager $entityManager;
    private TicketRepository $ticketRepository;


    public function __construct(CombinationMatcher $combinationMatcher, TicketRepository $ticketRepository, EntityManagerInterface $entityManager)
    {
        $this->combinationMatcher = $combinationMatcher;
        $this->entityManager = $entityManager;
        $this->ticketRepository = $ticketRepository;
    }

    public function createTicketResult(array $tickets)
    {
        foreach($tickets as $ticket) {
            
            $matchedGameCombination = $this->combinationMatcher->createIntersectedCombination($ticket->getCombination()->getNumbers(), $ticket->getGameRound()->getDrawnCombination()->getNumbers());

            $ticket->setMatchedCombination($matchedGameCombination);
            $this->ticketRepository->add($ticket, true);
        }
    }
}