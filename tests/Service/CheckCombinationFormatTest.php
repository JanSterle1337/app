<?php

namespace App\Service\Tests;

use App\Service\CheckCombinationFormat;
use PHPUnit\Framework\TestCase;

class CheckCombinationFormatTest extends TestCase
{
    /** @test */
    public function testOptimalCase(): void 
    {
        $checkCombinationFormat = new CheckCombinationFormat();
        $result = $checkCombinationFormat->isCombinationValid("1, 20, 30, 32, 34, 50", 6);
        $this->assertSame(true, $result);
    }

    /** @test */
    public function testWrongInputedCombinationCase(): void 
    {
        $checkCombinationFormat = new CheckCombinationFormat();
        $result = $checkCombinationFormat->isCombinationValid("1,20,30,32,34,50", 6);
        $this->assertSame(false, $result);
    }

    /** @test */
    public function testWrongInputedRepetitionCase(): void 
    {
        $checkCombinationFormat = new CheckCombinationFormat();
        $result = $checkCombinationFormat->isCombinationValid("1, 20, 30, 32, 34, 50", 5);
        $this->assertSame(false, $result);

    }
}
