<?php

namespace App\Controller;

use App\Entity\Secret;
use App\Entity\Vote;
use App\Entity\User;
use App\Form\ProfileEditType;
use App\Repository\SecretRepository;
use App\Repository\VoteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SecretRepository $secretRepository,
        private readonly VoteRepository $voteRepository
    ) {
    }

    #[Route('', name: 'app_profile_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Récupérer les statistiques de manière sécurisée
        $stats = [
            'secrets_count' => $this->secretRepository->count(['user' => $user]),
            'votes_count' => $this->voteRepository->count(['user' => $user]),
            'rooms_count' => $user->getRooms()->count()
        ];

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }


    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nickname = $form->get('nickname')->getData();
            $existingUser = $this->userRepository->findOneBy(['nickname' => $nickname]);

            if ($existingUser && $existingUser !== $user) {
                $this->addFlash('error', 'This nickname is already taken.');
                return $this->redirectToRoute('app_profile_edit');
            }

            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated.');
            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render(
            'profile/edit.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route('/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete-profile', $request->request->get('_token'))) {
            $user = $this->getUser();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->container->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

            $this->addFlash('success', 'Your account has been deleted.');
            return $this->redirectToRoute('app_home');
        }

        $this->addFlash('error', 'Invalid token');
        return $this->redirectToRoute('app_profile_show');
    }
}
