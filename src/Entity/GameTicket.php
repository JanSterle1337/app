<?php

namespace App\Entity;

use App\Service\BoundaryChecker;
use RuntimeException as GlobalRuntimeException;

//GameTicket is an object that holds the information of user's choosen combination.
//This combination has to follow specific game rules otherwise the ticker is not valid
class GameTicket
{
    private Combination $combination;

    public function __construct(Combination $combination, Game $game, BoundaryChecker $boundaryChecker)
    {
        if (!$boundaryChecker->isWithIn($combination->getNumbers(), $game->getMinimumNumber(), $game->getMaximumNumber())) {

            throw new GlobalRuntimeException();
        }

        if (count($combination->getNumbers()) > $game->getHowManyNumbers()) {

            throw new GlobalRuntimeException("There was a problem with your combination");
        }

        $this->combination = $combination;
    }

    public function getCombination(): Combination
    {
        return $this->combination;
    }

}