<?php

namespace App\Tests;

use App\Entity\Game;
use App\Entity\GameCombination;
use App\Service\Drawer;
use App\Service\DuplicateNumberChecker;
use PHPUnit\Framework\TestCase;

class DrawerTest extends TestCase
{
    public function testDrawer(): void
    {
        $duplicateNumberChecker = new DuplicateNumberChecker();

        $game = new Game();
        $game->setHowManyNumbers(7);
        $game->setMinimumNumber(1);
        $game->setMaximumNumber(39);

        $drawer = new Drawer($duplicateNumberChecker);
        $expectedCombination = $drawer->drawCombination($game);

        $this->assertIsObject($expectedCombination);
        $this->assertClassHasAttribute("numbers", GameCombination::class);

        $numbers = $expectedCombination->getNumbers();
        $howManyNumbers = count($numbers);

        $this->assertEquals($game->getHowManyNumbers(), $howManyNumbers);
    }
}
