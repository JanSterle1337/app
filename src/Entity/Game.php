<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $slug = null; //gameType objekt

    #[ORM\Column]
    private ?int $minimumNumber = null;

    #[ORM\Column]
    private ?int $maximumNumber = null;

    #[ORM\Column]
    private ?int $howManyNumbers = null;

    #[ORM\OneToMany(mappedBy: 'gameID', targetEntity: GameRound::class)]
    private Collection $gameRounds;

    public function __construct()
    {
        $this->gameRounds = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, GameRound>
     */
    public function getGameRounds(): Collection
    {
        return $this->gameRounds;
    }

    public function addGameRound(GameRound $gameRound): self
    {
        if (!$this->gameRounds->contains($gameRound)) {
            $this->gameRounds->add($gameRound);
            $gameRound->setGameID($this);
        }

        return $this;
    }

    public function removeGameRound(GameRound $gameRound): self
    {
        if ($this->gameRounds->removeElement($gameRound)) {
            // set the owning side to null (unless already changed)
            if ($gameRound->getGameID() === $this) {
                $gameRound->setGameID(null);
            }
        }

        return $this;
    }
}
