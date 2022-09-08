<?php

namespace App\Service;

class CheckCombinationFormat
{
    public function checkComboFormat(string $data, int $repetition): bool
    {
        $repetition -= 1;
        $pattern = sprintf("/^(([1-9]0?)+, ){%s}([1-9]0?)+$/", $repetition);

        if (preg_match($pattern, $data)) {
            return true;
        }

        return false;
    }
}