<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Service\ThemeGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeGeneratorController extends AbstractController
{
    private ThemeGeneratorService $themeGeneratorService;
    private EntityManagerInterface $entityManager;

    public function __construct(ThemeGeneratorService $themeGeneratorService, EntityManagerInterface $entityManager)
    {
        $this->themeGeneratorService = $themeGeneratorService;
        $this->entityManager = $entityManager;
    }

    #[Route('/theme-generator', name: 'theme_generator', methods: ['POST'])]
    public function generateTheme(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['prompt']) || empty($data['prompt'])) {
            return new JsonResponse(['error' => 'Le prompt est requis'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $themeText = $this->themeGeneratorService->generateTheme($data['prompt']);

            return new JsonResponse([
                'theme' => $themeText,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/theme-random', name: 'theme_random', methods: ['GET'])]
    public function randomTheme(): JsonResponse
    {
        $themeRepository = $this->entityManager->getRepository(Theme::class);
        $themes = $themeRepository->findAll();
        if (empty($themes)) {
            return new JsonResponse(['error' => 'No themes available.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $randomTheme = $themes[array_rand($themes)];

        return new JsonResponse([
            'theme' => $randomTheme->getQuestion(),
            'themeId' => $randomTheme->getId()
        ], Response::HTTP_OK);
    }
}
