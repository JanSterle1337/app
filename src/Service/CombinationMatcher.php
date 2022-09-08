<?php 
namespace App\Service;

use App\Entity\GameCombination;

class CombinationMatcher ///combinationMatcher
{
    private DuplicateNumberChecker $duplicateNumberChecker;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker)
    {
        $this->duplicateNumberChecker = $duplicateNumberChecker;
    }

    /**
     * @param array<int> $ticketNumbers
     * @param array<int> $gameRoundNumbers
     */
    public function createIntersectedCombination(array $ticketNumbers, array $gameRoundNumbers): GameCombination//first, second
    {
        $matchedNumbers = array_intersect($ticketNumbers,$gameRoundNumbers);
        sort($matchedNumbers); //converts array to an indexed normalized array
        
        return new GameCombination($this->duplicateNumberChecker, $matchedNumbers);
    }
}