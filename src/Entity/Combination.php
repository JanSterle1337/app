<?php

namespace App\Entity;

use App\Repository\CombinationRepository;
use App\Service\BoundaryChecker;
use App\Service\DuplicateNumberChecker;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CombinationRepository::class)]
class Combination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $numbers = [];

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker, array $numbers)
    {
        if ($duplicateNumberChecker->hasDuplicates($numbers)) {

            throw new \Exception("Your combination contains duplicate numbers");

        }

        $this->setNumbers($numbers);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumbers(): array
    {
        return $this->numbers;
    }

    public function setNumbers(?array $numbers): self
    {
        $this->numbers = $numbers;

        return $this;
    }

    public function numbersLength(): int 
    {
        return count($this->getNumbers());
    }
}
