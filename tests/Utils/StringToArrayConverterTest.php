<?php

namespace App\Utils\Tests;

use App\Utils\PrintArrayNumbersInString;
use PHPUnit\Framework\TestCase;

class StringToArrayConverterTest extends TestCase
{
    public function testOptimal(): void
    {
        $printArrayNumbersInString = new PrintArrayNumbersInString();
        $numbers = [1, 2, 10, 50, 90];

        $stringifiedNumbers = $printArrayNumbersInString->convert($numbers);

        $this->assertEquals("1 2 10 50 90", $stringifiedNumbers);
    }

    public function testEmpty(): void 
    {
        $printArrayNumbersInString = new PrintArrayNumbersInString();
        $numbers = [];

        $stringifiedNumbers = $printArrayNumbersInString->convert($numbers);

        $this->assertEquals("", $stringifiedNumbers);
    }

    public function testNull(): void 
    {
        $printArrayNumbersInString = new PrintArrayNumbersInString();
        $numbers = null;

        $stringifiedNumbers = $printArrayNumbersInString->convert($numbers);

        $this->assertEquals("", $stringifiedNumbers);
    }
    
}
