<?php

namespace App\Service;

class DuplicateNumberChecker
{
    /***
     * @param array<int> $data
     */
    public function hasDuplicates(array $data): bool
    {
        if (count($data) == count(array_unique($data))) {
            return false;
        }

        return true;
    }
}