<?php

namespace App\Controller\Api;

use App\Repository\PokedexRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api', name: 'api_')]
class PokemonController extends AbstractController
{
    private $security;
    private $pokedexRepository;

    public function __construct(Security $security, PokedexRepository $pokedexRepository)
    {
        $this->security = $security;
        $this->pokedexRepository = $pokedexRepository;
    }

    #[Route('/pokemons', name: 'get_user_pokemons', methods: ['GET'])]
    public function getUserPokemons(): JsonResponse
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return $this->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $userPokemons = $this->pokedexRepository->findBy(['user' => $user]);
        
        $pokemonData = array_map(function($pokedex) {
            return [
                'id' => $pokedex->getId(),
                'pokemon' => [
                    'id' => $pokedex->getPokemon()->getId(),
                    'name' => $pokedex->getPokemon()->getName(),
                    'type' => $pokedex->getPokemon()->getType(),
                    'image' => $pokedex->getPokemon()->getImage()
                ],
                'nickname' => $pokedex->getUser()->getUsername(),
                'level' => $pokedex->getLevel()
            ];
        }, $userPokemons);

        return $this->json([
            'pokemons' => $pokemonData
        ]);
    }

    #[Route('/pokemons/{id}', name: 'get_user_pokemon_by_id', methods: ['GET'])]
    public function getUserPokemonById(int $id): JsonResponse
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return $this->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $pokedex = $this->pokedexRepository->findOneBy([
            'user' => $user,
            'id' => $id
        ]);

        if (!$pokedex) {
            return $this->json([
                'message' => 'Pokemon not found or does not belong to user'
            ], 404);
        }

        $pokemonData = [
            'id' => $pokedex->getId(),
            'pokemon' => [
                'id' => $pokedex->getPokemon()->getId(),
                'name' => $pokedex->getPokemon()->getName(),
                'type' => $pokedex->getPokemon()->getType(),
                'image' => $pokedex->getPokemon()->getImage()
            ],
            'nickname' => $pokedex->getUser()->getUsername(),
            'level' => $pokedex->getLevel()
        ];

        return $this->json($pokemonData);
    }
}