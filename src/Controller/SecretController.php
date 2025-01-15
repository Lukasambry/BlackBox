<?php

namespace App\Controller;

use App\Entity\Secret;
use App\Form\SecretType;
use App\Repository\SecretRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secret')]
final class SecretController extends AbstractController
{
    #[Route(name: 'app_secret_index', methods: ['GET'])]
    public function index(SecretRepository $secretRepository): Response
    {
        return $this->render('secret/index.html.twig', [
            'secrets' => $secretRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_secret_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $secret = new Secret();
        $form = $this->createForm(SecretType::class, $secret);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($secret);
            $entityManager->flush();

            return $this->redirectToRoute('app_secret_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secret/new.html.twig', [
            'secret' => $secret,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_secret_show', methods: ['GET'])]
    public function show(Secret $secret): Response
    {
        return $this->render('secret/show.html.twig', [
            'secret' => $secret,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_secret_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Secret $secret, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SecretType::class, $secret);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_secret_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secret/edit.html.twig', [
            'secret' => $secret,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_secret_delete', methods: ['POST'])]
    public function delete(Request $request, Secret $secret, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secret->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($secret);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_secret_index', [], Response::HTTP_SEE_OTHER);
    }
}
