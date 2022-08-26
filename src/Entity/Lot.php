<?php

namespace App\Entity;

use App\Service\BoundaryChecker;
use App\Service\CombinationGenerator;
use App\Service\DuplicateNumberChecker;


//Lot is a process where a new combination is created by a set of rules of specific game
class Lot  //aka žreb
{
    private BoundaryChecker $boundaryChecker;
    private DuplicateNumberChecker $duplicateNumberChecker;
    private Combination $combination;

    public function __construct
    (
        CombinationGenerator $combinationGenerator,
        BoundaryChecker $boundaryChecker,
        DuplicateNumberChecker $duplicateNumberChecker,
        Game $game
    )
    {
        $this->boundaryChecker = $boundaryChecker;
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $combination = $combinationGenerator->drawCombination($game);
        $this->combination= $combination;
        //CombinationGenerator = new CombinationGenerator()
    }

    public function getCombination(): Combination
    {
        return $this->combination;
    }


}