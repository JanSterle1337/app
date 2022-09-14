<?php 
namespace App\Service;

use App\Entity\Combination;

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
    public function createIntersectedCombination(array $ticketNumbers, array $gameRoundNumbers): Combination//first, second
    {
        $matchedNumbers = array_intersect($ticketNumbers,$gameRoundNumbers);
        sort($matchedNumbers); //converts array to an indexed normalized array
        
        return new Combination($this->duplicateNumberChecker, $matchedNumbers);
    }
}