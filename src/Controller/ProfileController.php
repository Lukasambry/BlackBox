<?php

namespace App\Controller;

use App\Form\ProfileEditType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;



#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('', name: 'app_profile_show', methods: ['GET', 'POST'])]
    public function show(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Gérer la soumission du formulaire d'édition
        if ($request->isMethod('POST')) {
            $nickname = $request->request->get('nickname');
            $email = $request->request->get('email');

            // Vérifier si le nickname est déjà pris
            $existingUser = $this->userRepository->findOneBy(['nickname' => $nickname]);
            if ($existingUser && $existingUser !== $user) {
                $this->addFlash('error', 'Ce pseudo est déjà utilisé.');
                return $this->redirectToRoute('app_profile_show');
            }

            // Mettre à jour l'utilisateur
            $user->setNickname($nickname);
            $user->setEmail($email);

            $this->entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour.');

            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
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
