<?php 
namespace App\Utils\Tests;

use PHPUnit\Framework\TestCase;
use App\Utils\PrintArrayNumbersInString;

class PrintArrayNumbersInStringTest extends TestCase
{
    public function testOptimalCase(): void 
    {
        $printArrayNumbersInString = new PrintArrayNumbersInString();
        $numbers = $printArrayNumbersInString->convert([1,30,20,33]);

        $this->assertEquals("1 30 20 33", $numbers);
    }

    public function testNullCase(): void 
    {
        $printArrayNumbersInString = new PrintArrayNumbersInString();
        $numbers = $printArrayNumbersInString->convert(null);

        $this->assertEquals("", $numbers);
    }
}