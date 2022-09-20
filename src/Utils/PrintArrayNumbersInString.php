<?php 
namespace App\Utils;

class PrintArrayNumbersInString 
{
    /**
     * @return array<int>
     */
    public function convert(?array $numbers): string 
    {
        $str = "";

        if ($numbers !== null) {

            foreach ($numbers as $number) {
                $str .= $number . " ";
            }
            
        }
        

        return trim($str);
    }
}