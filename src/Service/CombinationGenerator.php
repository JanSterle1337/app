<?php
namespace App\Service;

use App\Entity\Combination;
use App\Entity\Game;

class CombinationGenerator
{
    private DuplicateNumberChecker $duplicateNumberChecker;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker) //rules and uniqueNumbers method
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
    }

    public function drawCombination(Game $game): Combination //Combination object drawCombination
    {
        $data = [];
        do {
            $number = random_int($game->getMinimumNumber(),$game->getMaximumNumber());

            if (!in_array($number, $data)) {
                $data[] = $number;
            }

        } while (count($data) < $game->getHowManyNumbers());

        return new Combination($data, $this->duplicateNumberChecker);

    }
}