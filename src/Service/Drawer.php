<?php 
namespace App\Service;

use App\Entity\Game;
use App\Entity\GameCombination;

class Drawer 
{
    private DuplicateNumberChecker $duplicateNumberChecker;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker)
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
    }

    public function drawCombination(Game $game): GameCombination
    {
        $numbers = [];

        do {
            $number = random_int($game->getMinimumNumber(),$game->getMaximumNumber());

            if (!in_array($number, $numbers)) {
                $numbers[] = $number;
            }

        } while (count($numbers) < $game->getHowManyNumbers());

        return new GameCombination($this->duplicateNumberChecker, $numbers);
    }

}