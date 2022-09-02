<?php

namespace App\Utils;

class StringToArrayConverter
{
    /**
     * @return array<int>
     */
    public function converter($find, $replace, $data): array
    {
        $data = str_replace($find, $replace, $data);
        
        $combinationNumbers = explode(' ', $data);

        return $combinationNumbers;
    }
}