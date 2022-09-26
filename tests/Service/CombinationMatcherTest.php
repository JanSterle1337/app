<?php

namespace App\Service\Tests;

use App\Entity\Combination;
use App\Service\CombinationMatcher;
use App\Service\DuplicateNumberChecker;
use PHPUnit\Framework\TestCase;

class CombinationMatcherTest extends TestCase
{
    public function testOptimalNumbers(): void
    {
        $duplicateNumberChecker = new DuplicateNumberChecker();
        $combinationMatcher = new CombinationMatcher($duplicateNumberChecker);

        $expectedCombination = new Combination($duplicateNumberChecker, [4, 5]);
        $combination = $combinationMatcher->createIntersectedCombination([1, 2, 3, 4, 5], [4, 6, 5, 10, 11]);

        $this->assertIsObject($combination);
        $this->assertEquals($expectedCombination, $combination);
    }

    public function testDoesntMatchAnyNumbers(): void 
    {
        $duplicateNumberChecker = new DuplicateNumberChecker();
        $combinationMatcher = new CombinationMatcher($duplicateNumberChecker);

        $expectedCombination = new Combination($duplicateNumberChecker, []);
        $combination = $combinationMatcher->createIntersectedCombination([1, 2, 3, 4, 5], [10, 20, 30, 40, 50]);

        $this->assertIsObject($combination);
        $this->assertEquals($expectedCombination, $combination);
    }
}
