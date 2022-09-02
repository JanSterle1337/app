<?php

namespace App\Entity;

use App\Repository\GameCombinationRepository;
use App\Service\BoundaryChecker;
use App\Service\DuplicateNumberChecker;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameCombinationRepository::class)]
class GameCombination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $numbers = [];

    #[ORM\OneToOne(mappedBy: 'combination', cascade: ['persist', 'remove'])]
    private ?Ticket $ticket = null;

    public function __construct(DuplicateNumberChecker $duplicateNumberChecker, array $numbers)
    {
        if ($duplicateNumberChecker->hasDuplicates($numbers)) {

            throw new \Exception("Your number combination has duplicate numbers");

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

    private function setNumbers(?array $numbers): self
    {
        $this->numbers = $numbers;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket, BoundaryChecker $boundaryChecker): self
    {
        // unset the owning side of the relation if necessary
        if ($ticket === null && $this->ticket !== null) {
            $this->ticket->setCombination(null);
        }

        // set the owning side of the relation if necessary
        if ($ticket !== null && $ticket->getCombination() !== $this) {
            $ticket->setCombination($this, $boundaryChecker);
        }

        $this->ticket = $ticket;

        return $this;
    }
}
