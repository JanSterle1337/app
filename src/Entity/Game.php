<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $minimumNumber = null;

    #[ORM\Column]
    private ?int $maximumNumber = null;

    #[ORM\Column]
    private ?int $howManyNumbers = null;

    public function __toString()
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getMinimumNumber(): ?int
    {
        return $this->minimumNumber;
    }

    public function setMinimumNumber(int $minimumNumber): self
    {
        $this->minimumNumber = $minimumNumber;

        return $this;
    }

    public function getMaximumNumber(): ?int
    {
        return $this->maximumNumber;
    }

    public function setMaximumNumber(int $maximumNumber): self
    {
        $this->maximumNumber = $maximumNumber;

        return $this;
    }

    public function getHowManyNumbers(): ?int
    {
        return $this->howManyNumbers;
    }

    public function setHowManyNumbers(int $howManyNumbers): self
    {
        $this->howManyNumbers = $howManyNumbers;

        return $this;
    }
}
