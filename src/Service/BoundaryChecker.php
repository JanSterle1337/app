<?php

namespace App\Service;

class BoundaryChecker
{
    /**
     * @param array<int> $data
     *
     */
    public function isWithIn(array $data, int $min, int $max): bool
    {
        foreach ($data as $number) {

            if ($number < $min || $number > $max) {
                return false;
            }

        }

        return true;
    }
}