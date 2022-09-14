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
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Combination $combination = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private  ?Combination $matchedCombination = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameRound $gameRound = null;

    public function __construct(GameRound $gameRound, User $user,Combination $combination)
    {
        $this->gameRound = $gameRound;
        $this->user = $user;
        $this->combination = $combination;        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getCombination(): ?Combination
    {
        return $this->combination;
    }

    public function setCombination(Combination $combination): self
    {
        $this->combination = $combination;

        return $this;
    }

    public function setMatchedCombination(Combination $combination): self
    {
        $this->matchedCombination = $combination;

        return $this;
    }

    public function getMatchedCombination(): ?Combination
    {
        return $this->matchedCombination;
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
}
