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

    public function createIntersectedCombination(Combination $first, Combination $second): Combination //first, second
    {
        $matchedNumbers = array_intersect($first->getNumbers(),$second->getNumbers());
        sort($matchedNumbers); //converts array to an indexed normalized array
        return new Combination($matchedNumbers, $this->duplicateNumberChecker);
    }
}