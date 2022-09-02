<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use App\Service\BoundaryChecker;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    #[ORM\OneToOne(inversedBy: 'ticket', cascade: ['persist', 'remove'])]
    private ?GameCombination $combination = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCombination(): ?GameCombination
    {
        return $this->combination;
    }

    public function setCombination(?GameCombination $combination, BoundaryChecker $boundaryChecker = null): self
    {
        if ($combination === null) {

            $this->combination = $combination;

            return $this;

        }

        if (!$boundaryChecker->isWithIn($combination->getNumbers(), $this->gameRoundID->getGameID()->getMinimumNumber(), $this->gameRoundID->getGameID()->getMaximumNumber())) {
            throw new RuntimeException("The combination contains numbers that are either to big or too small for the current game rules");
        }

        if (count($combination->getNumbers()) > $this->gameRoundID->getGameID()->getHowManyNumbers()) {
            throw new RuntimeException("Your combination has too many numbers for the current game");
        }

        $this->combination = $combination;

        return $this;
    }
}
