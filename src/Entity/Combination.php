<?php 
namespace App\Entity;

use App\Service\DuplicateNumberChecker;
use Exception;

class Combination 
{
    private array $numbers;

    /**
     * @param array<int> $data
     */
    public function __construct(array $data, DuplicateNumberChecker $duplicateNumberChecker) //array numbers
    {
        if ($duplicateNumberChecker->hasDuplicates($data)) {
            throw new Exception("Your input is incorrect");
        }

        $this->numbers = $data;
    }

    public function getNumbers(): array
    {
        return $this->numbers;
    }
}
