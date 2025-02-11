<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Form\BattleType;
use App\Repository\BattleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/battle')]
final class BattleController extends AbstractController
{
    #[Route(name: 'app_battle_index', methods: ['GET'])]
    public function index(BattleRepository $battleRepository): Response
    {
        return $this->render('battle/index.html.twig', [
            'battles' => $battleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_battle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $battle = new Battle();
        $form = $this->createForm(BattleType::class, $battle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($battle);
            $entityManager->flush();

            return $this->redirectToRoute('app_battle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('battle/new.html.twig', [
            'battle' => $battle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_battle_show', methods: ['GET'])]
    public function show(Battle $battle): Response
    {
        return $this->render('battle/show.html.twig', [
            'battle' => $battle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_battle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Battle $battle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BattleType::class, $battle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_battle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('battle/edit.html.twig', [
            'battle' => $battle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_battle_delete', methods: ['POST'])]
    public function delete(Request $request, Battle $battle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$battle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($battle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_battle_index', [], Response::HTTP_SEE_OTHER);
    }
}
