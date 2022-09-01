<?php
namespace App\Entity;

use App\Service\CombinationGenerator;

//Lot is a process where a new combination is created by a set of rules of specific game
class Lot  //aka žreb ///drawer
{
    private Combination $combination;

    public function __construct
    (
        CombinationGenerator $combinationGenerator,
        Game $game
    )
    {
        $combination = $combinationGenerator->drawCombination($game);
        $this->combination= $combination;
    }


    public function getCombination(): Combination
    {
        return $this->combination;
    }
}