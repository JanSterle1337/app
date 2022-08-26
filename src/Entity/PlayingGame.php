<?php

namespace App\Entity;

use App\Repository\PlayingGameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayingGameRepository::class)]
class PlayingGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $combinations = [];

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $Game = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $playedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCombinations(): array
    {
        return $this->combinations;
    }

    public function setCombinations(array $combinations): self
    {
        $this->combinations = $combinations;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(?Game $Game): self
    {
        $this->Game = $Game;

        return $this;
    }

    public function getPlayedAt(): ?\DateTimeInterface
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeInterface $playedAt): self
    {
        $this->playedAt = $playedAt;

        return $this;
    }
}
