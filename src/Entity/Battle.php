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

    #[ORM\Column(length: 255)]
    private ?string $winner = null;

    #[ORM\Column]
    private ?int $enemyLevel = null;

    #[ORM\Column]
    private ?int $enemyStrength = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEnemyLevel(): ?int
    {
        return $this->enemyLevel;
    }

    public function setEnemyLevel(int $enemyLevel): static
    {
        $this->enemyLevel = $enemyLevel;

        return $this;
    }

    public function getEnemyStrength(): ?int
    {
        return $this->enemyStrength;
    }

    public function setEnemyStrength(int $enemyStrength): static
    {
        $this->enemyStrength = $enemyStrength;

        return $this;
    }
}
