<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Secret;
use App\Entity\Vote;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Repository\ThemeRepository;
use App\Security\Voter\RoomVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

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
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ThemeRepository $themeRepository
    ): Response {
        $this->denyAccessUnlessGranted(RoomVoter::CREATE, null);

        $room = new Room();
        $room->setOwner($this->getUser());
        $room->addPlayer($this->getUser());

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customTheme = $request->request->get('customTheme');
            $selectedThemeId = $request->request->get('selectedThemeId');

            if (!empty($customTheme)) {
                $theme = new \App\Entity\Theme();
                $theme->setQuestion($customTheme);
                $theme->setCreatedAt(new \DateTimeImmutable());
                $theme->setUpdatedAt(new \DateTimeImmutable());
                $entityManager->persist($theme);

                $room->setTheme($theme);
            } elseif (!empty($selectedThemeId)) {
                $theme = $themeRepository->find($selectedThemeId);
                if ($theme) {
                    $room->setTheme($theme);
                }
            }

            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('app_room_show', [
                'id' => $room->getId(),
                'theme' => $customTheme,
            ]);
        }

        return $this->render('room/new.html.twig', [
        'room' => $room,
        'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_room_show', methods: ['GET'])]
    public function show(Room $room): Response
    {
        $this->denyAccessUnlessGranted(RoomVoter::VIEW, $room);

        if ($room->isStarted()) {
            $this->denyAccessUnlessGranted(RoomVoter::PLAY, $room);
            return $this->render('room/game.html.twig', [
            'room' => $room,
            ]);
        }

        return $this->render('room/show.html.twig', [
        'room' => $room,
        ]);
    }

    #[Route('/join/{inviteCode}', name: 'app_room_join', methods: ['GET'])]
    public function join(string $inviteCode, RoomRepository $roomRepository, EntityManagerInterface $entityManager): Response
    {
        $room = $roomRepository->findOneBy(['inviteCode' => $inviteCode, 'isActive' => true]);

        if (!$room) {
            throw $this->createNotFoundException('Room not found.');
        }

        $this->denyAccessUnlessGranted(RoomVoter::JOIN, $room);

        $room->addPlayer($this->getUser());
        $entityManager->flush();

        return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
    }

    #[Route('/start/{id}', name: 'app_room_start', methods: ['POST'])]
    public function start(
        Room $room,
        EntityManagerInterface $entityManager,
        ThemeRepository $themeRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted(RoomVoter::START, $room);

        if (!$room->getTheme()) {
            $themes = $themeRepository->findAll();
            if (empty($themes)) {
                return new JsonResponse(['error' => 'No themes available.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $randomTheme = $themes[array_rand($themes)];
            $room->setTheme($randomTheme);
        }

        $now = new \DateTimeImmutable();
        $room->setCurrentState(Room::STATE_STARTING);
        $room->setStartingPhaseStartedAt($now);
        $room->setIsStarted(true);

        $room->setTheme($randomTheme);
        $room->setCurrentState(Room::STATE_STARTING);
        $room->setStartingPhaseStartedAt($now);
        $room->setIsStarted(true);

        try {
            $entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'currentState' => $room->getCurrentState(),
                'theme' => [
                    'id' => $room->getTheme()->getId(),
                    'question' => $room->getTheme()->getQuestion()
                ],
                'startTime' => $now->format('c')
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Failed to start game: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

        #[Route('/{id}/submit-anecdote', name: 'app_room_submit_anecdote', methods: ['POST'])]
    public function submitAnecdote(Request $request, Room $room, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted(RoomVoter::PLAY, $room);

        $data = json_decode($request->getContent(), true);
        $content = $data['content'] ?? null;

        if (!$content) {
            return new JsonResponse(['error' => 'No content provided.'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($room->getSecrets() as $secret) {
            if ($secret->getUser() === $this->getUser()) {
                return new JsonResponse(['error' => 'You have already submitted an anecdote.'], Response::HTTP_BAD_REQUEST);
            }
        }

        $secret = new Secret();
        $secret->setContent($content);
        $secret->setUser($this->getUser());
        $secret->setRoom($room);
        $secret->setCreatedAt(new \DateTimeImmutable());
        $secret->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($secret);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

        #[Route('/players/{id}', name: 'app_room_players', methods: ['GET'])]
    public function getPlayers(Room $room): JsonResponse
    {
        $this->denyAccessUnlessGranted(RoomVoter::VIEW, $room);

        $players = [];
        foreach ($room->getPlayers() as $player) {
            $players[] = [
            'id' => $player->getId(),
            'nickname' => $player->getNickname(),
            ];
        }

        return new JsonResponse($players);
    }

        #[Route('/game-state/{id}', name: 'app_room_game_state', methods: ['GET'])]
    public function getGameState(Room $room, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted(RoomVoter::PLAY, $room);

        $now = new \DateTimeImmutable();
        $data = [
        'currentState' => $room->getCurrentState(),
        'isStarted' => $room->isStarted(),
        'theme' => $room->getTheme() ? [
            'id' => $room->getTheme()->getId(),
            'question' => $room->getTheme()->getQuestion()
        ] : null,
        ];

        if ($room->getCurrentState() === Room::STATE_STARTING && $room->getStartingPhaseStartedAt()) {
            $elapsed = $now->getTimestamp() - $room->getStartingPhaseStartedAt()->getTimestamp();
            $data['remainingTime'] = max(0, 10 - $elapsed);

            if ($elapsed >= 10) {
                $room->setCurrentState(Room::STATE_PLAYING);
                $room->setPlayingPhaseStartedAt($now);
                $entityManager->flush();
                $data['currentState'] = Room::STATE_PLAYING;
            }
        }

        if ($room->getCurrentState() === Room::STATE_PLAYING && $room->getPlayingPhaseStartedAt()) {
            $elapsed = $now->getTimestamp() - $room->getPlayingPhaseStartedAt()->getTimestamp();
            $data['remainingTime'] = max(0, 30 - $elapsed);

            $allPlayersAnswered = count($room->getSecrets()) === count($room->getPlayers());
            $timeIsUp = $elapsed >= 30;

            if ($allPlayersAnswered || $timeIsUp) {
                $room->setCurrentState(Room::STATE_VOTING);
                $room->setCurrentVotingSecretIndex(0);
                $room->setCurrentVotingStartedAt($now);
                $room->setVotingPhaseStartedAt($now);
                $entityManager->flush();
                $data['currentState'] = Room::STATE_VOTING;
            }
        }

        if ($room->getCurrentState() === Room::STATE_VOTING) {
            $currentSecret = $room->getCurrentVotingSecret();
            $data['voting'] = [
            'currentSecretIndex' => $room->getCurrentVotingSecretIndex(),
            'totalSecrets' => count($room->getSecrets()),
            'remainingTime' => $room->getRemainingVotingTime(),
            'currentSecret' => $currentSecret ? [
                'id' => $currentSecret->getId(),
                'content' => $currentSecret->getContent(),
                'hasUserVoted' => $currentSecret->getVotes()->exists(
                    fn($_, $vote) => $vote->getUser() === $this->getUser()
                ),
                'isOwnSecret' => $currentSecret->getUser() === $this->getUser()
            ] : null
            ];

            if ($room->shouldMoveToNextSecret()) {
                $totalSecrets = count($room->getSecrets());
                $currentIndex = $room->getCurrentVotingSecretIndex();
                $nextIndex = $currentIndex + 1;

                if ($nextIndex >= $totalSecrets) {
                    $room->setCurrentState(Room::STATE_FINISHED);
                    $data['results'] = $this->getGameResults($room);
                } else {
                    $room->setCurrentVotingSecretIndex($nextIndex);
                    $room->setCurrentVotingStartedAt(new \DateTimeImmutable());
                }
                $entityManager->flush();
            }
        }

        if ($room->getCurrentState() === Room::STATE_FINISHED) {
            $data['results'] = $this->getGameResults($room);
        }

        $data['answeredCount'] = count($room->getSecrets());
        $data['totalPlayers'] = count($room->getPlayers());

        return new JsonResponse($data);
    }

        #[Route('/{id}/vote', name: 'app_room_vote', methods: ['POST'])]
    public function vote(Request $request, Room $room, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted(RoomVoter::PLAY, $room);

        if ($room->getCurrentState() !== Room::STATE_VOTING) {
            return new JsonResponse(['error' => 'Voting is not currently active'], Response::HTTP_BAD_REQUEST);
        }

        $currentSecret = $room->getCurrentVotingSecret();
        if (!$currentSecret) {
            return new JsonResponse(['error' => 'No secret currently being voted on'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($currentSecret->getVotes() as $vote) {
            if ($vote->getUser() === $this->getUser()) {
                return new JsonResponse(['error' => 'You have already voted for this secret'], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($currentSecret->getUser() === $this->getUser()) {
            return new JsonResponse(['error' => 'You cannot vote for your own secret'], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);
        $isPositive = $data['isPositive'] ?? false;

        $vote = new Vote();
        $vote->setUser($this->getUser());
        $vote->setSecret($currentSecret);
        $vote->setIsPositive($isPositive);

        $entityManager->persist($vote);
        $entityManager->flush();

        $totalPlayers = $room->getPlayers()->count();
        $totalVotesForCurrentSecret = $currentSecret->getVotes()->count();

        if ($totalVotesForCurrentSecret >= $totalPlayers - 1) { // -1 car l'auteur ne vote pas
            return $this->nextSecret($room, $entityManager);
        }

        return new JsonResponse(['success' => true]);
    }

    private function getGameResults(Room $room): array
    {
        $results = [];
        foreach ($room->getSecrets() as $secret) {
            $positiveVotes = 0;
            $negativeVotes = 0;
            foreach ($secret->getVotes() as $vote) {
                if ($vote->isPositive()) {
                    $positiveVotes++;
                } else {
                    $negativeVotes++;
                }
            }

            $results[] = [
            'id' => $secret->getId(),
            'content' => $secret->getContent(),
            'author' => $secret->getUser()->getNickname(),
            'positiveVotes' => $positiveVotes,
            'negativeVotes' => $negativeVotes,
            'score' => $positiveVotes - $negativeVotes
            ];
        }

        usort($results, function ($a, $b) {
            if ($a['score'] === $b['score']) {
                return $b['positiveVotes'] - $a['positiveVotes'];
            }
            return $b['score'] - $a['score'];
        });

        return $results;
    }

        #[Route('/{id}/next-secret', name: 'app_room_next_secret', methods: ['POST'])]
    public function nextSecret(Room $room, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted(RoomVoter::PLAY, $room);

        $data = [
        'success' => true,
        'currentState' => $room->getCurrentState(),
        ];

        if ($room->getCurrentState() !== Room::STATE_VOTING) {
            return new JsonResponse(['error' => 'Not in voting phase'], Response::HTTP_BAD_REQUEST);
        }

        $totalSecrets = count($room->getSecrets());
        $currentIndex = $room->getCurrentVotingSecretIndex() ?? -1;
        $nextIndex = $currentIndex + 1;

        if ($nextIndex >= $totalSecrets) {
            $room->setCurrentState(Room::STATE_FINISHED);
            $data['results'] = $this->getGameResults($room);
        } else {
            $room->setCurrentVotingSecretIndex($nextIndex);
            $room->setCurrentVotingStartedAt(new \DateTimeImmutable());
        }

        $entityManager->flush();

        $data['nextSecretIndex'] = $nextIndex;
        $data['isGameFinished'] = $nextIndex >= $totalSecrets;

        return new JsonResponse($data);
    }

        #[Route('/{id}/answered-count', name: 'app_room_answered_count', methods: ['GET'])]
    public function getAnsweredCount(Room $room): JsonResponse
    {
        $this->denyAccessUnlessGranted(RoomVoter::PLAY, $room);

        return new JsonResponse([
        'answeredCount' => count($room->getSecrets()),
        'totalPlayers' => count($room->getPlayers())
        ]);
    }
}
