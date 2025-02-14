<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "json")]
    private $type;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Pokedex>
     */
    #[ORM\OneToMany(targetEntity: Pokedex::class, mappedBy: 'pokemon')]
    private Collection $pokedexes;

    #[ORM\OneToOne(targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $evolution = null;

    public function __construct()
    {
        $this->pokedexes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): array
    {
        return $this->type;
    }

    public function setType(array $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Pokedex>
     */
    public function getPokedexes(): Collection
    {
        return $this->pokedexes;
    }

    public function addPokedex(Pokedex $pokedex): static
    {
        if (!$this->pokedexes->contains($pokedex)) {
            $this->pokedexes->add($pokedex);
            $pokedex->setPokemon($this);
        }

        return $this;
    }

    public function removePokedex(Pokedex $pokedex): static
    {
        if ($this->pokedexes->removeElement($pokedex)) {
            // set the owning side to null (unless already changed)
            if ($pokedex->getPokemon() === $this) {
                $pokedex->setPokemon(null);
            }
        }

        return $this;
    }

    public function getEvolution(): ?self
    {
        return $this->evolution;
    }

    public function setEvolution(?self $evolution): static
    {
        $this->evolution = $evolution;

        return $this;
    }
}
