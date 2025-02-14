<?php

namespace App\Controller;

use App\Entity\Pokedex;
use App\Form\PokedexType;
use App\Repository\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokedex')]
final class PokedexController extends AbstractController
{
    #[Route(name: 'app_pokedex_index', methods: ['GET'])]
    public function index(PokedexRepository $pokedexRepository): Response
    {
        return $this->render('pokedex/index.html.twig', [
            'pokedexes' => $pokedexRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pokedex_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pokedex = new Pokedex();
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pokedex);
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/new.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }

    #[Route('/pokedex/{id}/train', name: 'app_train_pokemon', methods: ['POST'])]
    public function trainPokemon(Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        // Aumentar la fuerza del pokemon en 10 puntos
        $pokedex->setStrength($pokedex->getStrength() + 10);

        // Guardar los cambios en la base de datos
        $entityManager->flush();

        // Mostrar un mensaje de éxito
        $message = '¡Pokemon entrenado!';
        $type = 'success';
        $this->addFlash($type, $message);

        // Redirigir a la página anterior (o a donde desees)
        return $this->redirectToRoute('app_pokedex_index');
    }

        #[Route('/{id}/evolve', name: 'app_pokedex_evolve', methods: ['POST'])]
        public function evolve(Pokedex $pokedex, EntityManagerInterface $entityManager): Response
        {
            if ($pokedex->getLevel() % 10 == 0 && $pokedex->getLastEvolutionLevel() !== $pokedex->getLevel()) {
                $pokemon = $pokedex->getPokemon();
                $evolution = $pokemon->getEvolution();

                if ($evolution) {
                    $pokedex->setPokemon($evolution);
                    $pokedex->setLastEvolutionLevel($pokedex->getLevel());

                    $entityManager->flush();

                    $message = 'El pokemon ha evolucionado a ' . $evolution->getName() . '!';
                    $type ='success';   
                } else {
                    $message = 'Este Pokémon no puede evolucionar más.';
                    $type = 'warning';
                }
                
                $this->addFlash($type, $message);
                return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
            }
        }

    #[Route('/{id}', name: 'app_pokedex_show', methods: ['GET'])]
    public function show(Pokedex $pokedex): Response
    {
        return $this->render('pokedex/show.html.twig', [
            'pokedex' => $pokedex,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pokedex_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/edit.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pokedex_delete', methods: ['POST'])]
    public function delete(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pokedex->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pokedex);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
    }
}
