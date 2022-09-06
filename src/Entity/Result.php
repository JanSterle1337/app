<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $matchedCombination = [];

    #[ORM\OneToOne(inversedBy: 'result', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ticket $ticketID = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameRound $gameRound = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchedCombination(): array
    {
        return $this->matchedCombination;
    }

    public function setMatchedCombination(?array $matchedCombination): self
    {
        $this->matchedCombination = $matchedCombination;

        return $this;
    }

    public function getTicketID(): ?Ticket
    {
        return $this->ticketID;
    }

    public function setTicketID(Ticket $ticketID): self
    {
        $this->ticketID = $ticketID;

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
}
