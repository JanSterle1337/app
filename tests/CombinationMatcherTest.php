<?php

namespace App\Tests;

use App\Entity\GameCombination;
use App\Service\CombinationMatcher;
use App\Service\DuplicateNumberChecker;
use PHPUnit\Framework\TestCase;

class CombinationMatcherTest extends TestCase
{
    public function testOptimalNumbers(): void
    {
        $duplicateNumberChecker = new DuplicateNumberChecker();
        $combinationMatcher = new CombinationMatcher($duplicateNumberChecker);

        $expectedGameCombination = new GameCombination($duplicateNumberChecker, [4, 5]);
        $gameCombination = $combinationMatcher->createIntersectedCombination([1, 2, 3, 4, 5], [4, 6, 5, 10, 11]);

        $this->assertIsObject($gameCombination);
        $this->assertClassHasAttribute('numbers', GameCombination::class);
        $this->assertEquals($expectedGameCombination, $gameCombination);
    }

    public function testDoesntMatchAnyNumbers(): void 
    {
        $duplicateNumberChecker = new DuplicateNumberChecker();
        $combinationMatcher = new CombinationMatcher($duplicateNumberChecker);

        $expectedGameCombination = new GameCombination($duplicateNumberChecker, []);
        $gameCombination = $combinationMatcher->createIntersectedCombination([1, 2, 3, 4, 5], [10, 20, 30, 40, 50]);

        $this->assertIsObject($gameCombination);
        $this->assertClassHasAttribute('numbers', GameCombination::class);
        $this->assertEquals($expectedGameCombination, $gameCombination);
    }
}
