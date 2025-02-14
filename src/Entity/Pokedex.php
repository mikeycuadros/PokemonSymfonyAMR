<?php

namespace App\Entity;

use App\Repository\PokedexRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokedexRepository::class)]
class Pokedex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $level = 1;

    #[ORM\Column]
    private ?int $strength = 10;

    #[ORM\ManyToOne(inversedBy: 'pokedexes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'pokedexes')]
    private ?Pokemon $pokemon = null;

    #[ORM\Column(nullable: true)]
    private ?int $lastEvolutionLevel = null;

    #[ORM\Column]
    private ?bool $state = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): static
    {
        $this->strength = $strength;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): static
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getLastEvolutionLevel(): ?int
    {
        return $this->lastEvolutionLevel;
    }

    public function setLastEvolutionLevel(?int $lastEvolutionLevel): static
    {
        $this->lastEvolutionLevel = $lastEvolutionLevel;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): static
    {
        $this->state = $state;

        return $this;
    }
}
