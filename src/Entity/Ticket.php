<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $scheduledAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameCombination $combination = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameRound $gameRound = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $matchedNumbers = [];

    public function __construct(GameRound $gameRound, User $user, GameCombination $gameCombination)
    {
        $this->gameRound = $gameRound;
        $this->user = $user;
        $this->combination = $gameCombination;        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScheduledAt(): ?\DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(\DateTimeImmutable $scheduledAt): self
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCombination(): ?GameCombination
    {
        return $this->combination;
    }

    public function setCombination(GameCombination $combination): self
    {
        $this->combination = $combination;

        return $this;
    }

    public function getGameRound(): ?GameRound
    {
        return $this->gameRound;
    }

    public function setGameRound(?GameRound $gameRound): self
    {
        $this->gameRound = $gameRound;

        return $this;
    }

    public function getMatchedNumbers(): array
    {
        return $this->matchedNumbers;
    }

    public function setMatchedNumbers(?array $matchedNumbers): self
    {
        $this->matchedNumbers = $matchedNumbers;

        return $this;
    }
}
