<?php

namespace App\Controller;

use App\Entity\Pokedex;
use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/pokemon')]
final class PokemonController extends AbstractController
{
    #[Route(name: 'app_pokemon_index', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('pokemon/index.html.twig', [
            'pokemon' => $pokemonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pokemon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, ParameterBagInterface $params): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);
    
        // Obtener el directorio de las imágenes desde el parámetro
        $brochuresDirectory = $params->get('brochures_directory');
    
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
    
                try {
                    $image->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                }
    
                $pokemon->setImage($newFilename);
            }
    
            $entityManager->persist($pokemon);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('pokemon/new.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form,
        ]);
    }
    
    

    #[Route("/hunt", name: "app_hunt_pokemon", methods: ['GET'])]
    public function huntPokemon(EntityManagerInterface $entityManager): Response
    {
        $pokemon = $entityManager->getRepository(Pokemon::class)->findRandomPokemon();

        if (!$pokemon) {
            $message = 'No hay pokemon disponibles para capturar.';
            $type = 'error';
            $this->addFlash($type, $message);
            return $this->redirectToRoute('app_main');
        }

        return $this->render('pokemon/hunt.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route("/{id}/capture", name: "app_capture_pokemon",  methods: ['POST'])]
    public function attemptCapture(Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        
        $user = $this->getUser();
        $probability = rand(1, 100);

        if ($probability <= 60) {
            $pokedex = new Pokedex();
            $pokedex->setUser($user);
            $pokedex->setPokemon($pokemon);

            $entityManager->persist($pokedex);
            $entityManager->flush();

            $message = '¡Pokemon capturado!';
            $type = 'success';
        } else {
            $message = 'El pokemon escapó.';
            $type = 'error';
        }

        $this->addFlash($type, $message);
        return $this->redirectToRoute('app_hunt_pokemon', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_pokemon_show', methods: ['GET'])]
    public function show(Pokemon $pokemon): Response
    {
        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pokemon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokemon/edit.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pokemon_delete', methods: ['POST'])]
    public function delete(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pokemon->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pokemon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
