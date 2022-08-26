<?php

namespace App\Utils;

class StringToArrayConverter
{
    /**
     * @return array<int>
     */
    public function convert($find,$replace,$key, $data): array
    {

        $data[$key] = str_replace($find,$replace,$data[$key]);
        $data = explode(' ', $data[$key]);

        for($i = 0; $i < count($data); $i++)
        {
            $data[$i] = intval($data[$i]);
        }

        return $data;
    }
}