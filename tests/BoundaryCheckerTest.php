<?php

namespace App\Tests;

use App\Service\BoundaryChecker;
use PHPUnit\Framework\TestCase;

class BoundaryCheckerTest extends TestCase
{
    /** @test */
    public function testoptimalNumbers(): void
    {
        $numbers = [2, 3, 4, 5, 6, 7, 30, 20, 23, 50];
        $boundaryChecker = new BoundaryChecker();
        $result = $boundaryChecker->isWithIn($numbers, 1, 60);

        $this->assertSame(true, $result);
    }

    public function testOverBoundaryOrEqualNumbers(): void 
    {
        $numbers = [2, 3, 4, 5, 6, 7, 30, 20, 23, 50];
        $boundaryChecker = new BoundaryChecker();
        $result = $boundaryChecker->isWithIn($numbers,1, 50);

        $this->assertSame(true, $result);
    }

    public function testOverBoundaryNumbers(): void 
    {
        $numbers = [2, 3, 4, 5, 6, 7, 30, 20, 23, 50];
        $boundaryChecker = new BoundaryChecker();
        $result = $boundaryChecker->isWithIn($numbers,1, 40);

        $this->assertSame(false, $result);
    }

    public function testUnderBoundaryOrEqualNumbers(): void 
    {
        $numbers = [1, 2, 3, 4, 5, 6, 7, 30, 20, 23, 50];
        $boundaryChecker = new BoundaryChecker();
        $result = $boundaryChecker->isWithIn($numbers,1, 60);

        $this->assertSame(true, $result);
    }

    public function testUnderBoundaryNumbers(): void 
    {
        $numbers = [1, 2, 3, 4, -5, 6, 7, -30, 20, 23, 50];
        $boundaryChecker = new BoundaryChecker();
        $result = $boundaryChecker->isWithIn($numbers,1, 40);

        $this->assertSame(false, $result);
    }
}