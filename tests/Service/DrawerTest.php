<?php

namespace App\Service\Tests;

use App\Entity\Game;
use App\Entity\Combination;
use App\Service\Drawer;
use App\Service\DuplicateNumberChecker;
use PHPUnit\Framework\TestCase;

class DrawerTest extends TestCase
{
    public function testDrawer(): void //change name of method
    {
        $duplicateNumberChecker = new DuplicateNumberChecker();

        $game = new Game(); //preko konstruktorjev
        $game->setHowManyNumbers(7);
        $game->setMinimumNumber(1);
        $game->setMaximumNumber(39);

        $drawer = new Drawer($duplicateNumberChecker);
        $expectedCombination = $drawer->drawCombination($game);

        $this->assertIsObject($expectedCombination);

        //remove
        $this->assertClassHasAttribute("numbers", Combination::class); //combination shpuld include a method for length of array

        $numbers = $expectedCombination->getNumbers();
        $howManyNumbers = $expectedCombination->numbersLength();

        $this->assertEquals($game->getHowManyNumbers(), $howManyNumbers);
    }
}
