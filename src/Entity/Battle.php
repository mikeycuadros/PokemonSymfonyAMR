<?php

namespace App\Entity;

use App\Repository\BattleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BattleRepository::class)]
class Battle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Pokemon $enemy = null;

    #[ORM\Column(length: 255)]
    private ?string $winner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnemy(): ?Pokemon
    {
        return $this->enemy;
    }

    public function setEnemy(?Pokemon $enemy): static
    {
        $this->enemy = $enemy;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): static
    {
        $this->winner = $winner;

        return $this;
    }
}
