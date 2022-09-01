<?php

namespace App\Entity;

use App\Repository\GameRoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRoundRepository::class)]
class GameRound
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $drawnCombination = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $scheduledAt = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'gameRounds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $gameID = null;

    #[ORM\OneToMany(mappedBy: 'gameRoundID', targetEntity: Result::class)]
    private Collection $results;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    private ?bool $playedAlready = false;

    #[ORM\OneToMany(mappedBy: 'gameRoundID', targetEntity: Ticket::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrawnCombination(): array
    {
        return $this->drawnCombination;
    }

    public function setDrawnCombination(?array $drawnCombination): self
    {
        $this->drawnCombination = $drawnCombination;

        return $this;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGameID(): ?Game
    {
        return $this->gameID;
    }

    public function setGameID(?Game $gameID): self
    {
        $this->gameID = $gameID;

        return $this;
    }

    /**
     * @return Collection<int, Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setGameRoundID($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getGameRoundID() === $this) {
                $result->setGameRoundID(null);
            }
        }

        return $this;
    }

    public function isPlayedAlready(): ?bool
    {
        return $this->playedAlready;
    }

    public function setPlayedAlready(bool $playedAlready): self
    {
        $this->playedAlready = $playedAlready;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setGameRoundID($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getGameRoundID() === $this) {
                $ticket->setGameRoundID(null);
            }
        }

        return $this;
    }

}
