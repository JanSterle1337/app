<?php 
namespace App\Service;

use App\Entity\GameRound;
use App\Repository\GameRoundRepository;
use App\Repository\TicketRepository;
use App\Service\TicketToGameRoundCombinationMatcher;

class GameLauncher
{
    private DuplicateNumberChecker $duplicateNumberChecker;
    private GameRoundRepository $gameRoundRepository;
    private TicketRepository $ticketRepository;
    private TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher;

    public function __construct(
        DuplicateNumberChecker $duplicateNumberChecker, 
        GameRoundRepository $gameRoundRepository,
        TicketRepository $ticketRepository,
        TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher
    )
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->gameRoundRepository = $gameRoundRepository;
        $this->ticketRepository = $ticketRepository;
        $this->ticketToGameRoundCombinationMatcher = $ticketToGameRoundCombinationMatcher;
    }

    public function launch(GameRound $gameRound)
    {
        $drawer = new Drawer($this->duplicateNumberChecker);

        $gameCombination = $drawer->drawCombination($gameRound->getGame());
        $gameRound->setDrawnCombination($gameCombination);

        $this->gameRoundRepository->add($gameRound, true);

        $tickets = $this->ticketRepository->findByGameRound($gameRound);
        $this->ticketToGameRoundCombinationMatcher->createTicketResult($tickets);       
    }
} 
