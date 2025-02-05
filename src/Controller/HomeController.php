<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render(
            'home/index.html.twig', [
                'controller_name' => 'HomeController',
                'rooms' => $roomRepository->findBy(['isActive' => true, 'isPrivate' => false],['created_at' => 'DESC']),
            ]
        );
    }
}
