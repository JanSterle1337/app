<?php

namespace App\Service;

class CheckCombinationFormat
{
    public function isCombinationValid(string $data, int $repetition): bool //isValid poimenovanje npr
    {
        $repetition -= 1;
        /*PATTERN INFO
        regex that checks string which must contain
        as many numbers as the $repetion param
        ($repetion = 6, string must contain 6 numbers)
        between every number there must be comma, followed by space
        */
        $pattern = sprintf("/^(([1-9]0?)+, ){%s}([1-9]0?)+$/", $repetition);

        if (preg_match($pattern, $data)) {
            return true;
        }

        return false;
    }
}