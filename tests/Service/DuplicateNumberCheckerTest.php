<?php

namespace App\Service\Tests;

use App\Service\DuplicateNumberChecker;
use PHPUnit\Framework\TestCase;

class DuplicateNumberCheckerTest extends TestCase
{
    public function testNoDuplicates(): void
    {
        $duplicateChecker = new DuplicateNumberChecker();

        $result = $duplicateChecker->hasDuplicates([1, 2, 3]);
        $this->assertEquals(false, $result);
    }

    public function testHasDuplicates(): void 
    {
        $duplicateChecker = new DuplicateNumberChecker();

        $result = $duplicateChecker->hasDuplicates([1, 2, 3, 5, 3]);
        $this->assertEquals(true, $result);
    }
}
