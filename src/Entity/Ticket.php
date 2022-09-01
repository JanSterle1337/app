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

    #[ORM\Column(type: Types::ARRAY)]
    private array $playedCombination = [];

    #[ORM\Column]
    private ?int $gameID = null;

    #[ORM\Column(options: ['default' => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userID = null;

    #[ORM\OneToOne(mappedBy: 'ticketID', cascade: ['persist', 'remove'])]
    private ?Result $result = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameRound $gameRoundID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayedCombination(): array
    {
        return $this->playedCombination;
    }

    public function setPlayedCombination(array $playedCombination): self
    {
        $this->playedCombination = $playedCombination;

        return $this;
    }

    public function getGameID(): ?int
    {
        return $this->gameID;
    }

    public function setGameID(int $gameID): self
    {
        $this->gameID = $gameID;

        return $this;
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

    public function getUserID(): ?User
    {
        return $this->userID;
    }

    public function setUserID(?User $userID): self
    {
        $this->userID = $userID;

        return $this;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(Result $result): self
    {
        // set the owning side of the relation if necessary
        if ($result->getTicketID() !== $this) {
            $result->setTicketID($this);
        }

        $this->result = $result;

        return $this;
    }

    public function getGameRoundID(): ?GameRound
    {
        return $this->gameRoundID;
    }

    public function setGameRoundID(?GameRound $gameRoundID): self
    {
        $this->gameRoundID = $gameRoundID;

        return $this;
    }
}
