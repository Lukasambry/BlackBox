<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/room')]
final class RoomController extends AbstractController
{
    #[Route('/', name: 'app_room_index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findBy(['isActive' => true], ['created_at' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_room_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();
        $room->addPlayer($this->getUser());

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_room_show', methods: ['GET'])]
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/join/{inviteCode}', name: 'app_room_join', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function join(string $inviteCode, RoomRepository $roomRepository, EntityManagerInterface $entityManager): Response
    {
        $room = $roomRepository->findOneBy(['inviteCode' => $inviteCode, 'isActive' => true]);

        if (!$room) {
            throw $this->createNotFoundException('Room not found.');
        }

        if ($room->isStarted()) {
            $this->addFlash('error', 'This game has already started.');
            return $this->redirectToRoute('app_room_index');
        }

        if ($room->getPlayers()->count() >= $room->getMaxCapacity()) {
            $this->addFlash('error', 'This room is full.');
            return $this->redirectToRoute('app_room_index');
        }

        $room->addPlayer($this->getUser());
        $entityManager->flush();

        return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
    }

    #[Route('/start/{id}', name: 'app_room_start', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function start(Room $room, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($room->getPlayers()->count() < 2) {
            return new JsonResponse(['error' => 'Not enough players to start the game.'], Response::HTTP_BAD_REQUEST);
        }

        $room->setIsStarted(true);
        $room->setStartTime(new \DateTimeImmutable());
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/players/{id}', name: 'app_room_players', methods: ['GET'])]
    public function getPlayers(Room $room): JsonResponse
    {
        $players = [];
        foreach ($room->getPlayers() as $player) {
            $players[] = [
                'id' => $player->getId(),
                'nickname' => $player->getNickname(),
            ];
        }

        return new JsonResponse($players);
    }
}