<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    public const STATE_WAITING = 'waiting';
    public const STATE_STARTING = 'starting';
    public const STATE_PLAYING = 'playing';
    public const STATE_VOTING = 'voting';
    public const STATE_FINISHED = 'finished';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\Column]
    private ?bool $isPrivate = true;

    #[ORM\Column]
    private ?int $maxCapacity = 10;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 20)]
    private ?string $currentState = self::STATE_WAITING;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $startingPhaseStartedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $playingPhaseStartedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $votingPhaseStartedAt = null;

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;
        return $this;
    }

    public function isOwner(User $user): bool
    {
        return $this->owner === $user;
    }

    #[ORM\ManyToOne]
    private ?Theme $theme = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'rooms')]
    private Collection $players;

    #[ORM\OneToMany(targetEntity: Secret::class, mappedBy: 'room', orphanRemoval: true)]
    private Collection $secrets;

    #[ORM\Column(length: 32, unique: true)]
    private ?string $inviteCode = null;

    #[ORM\Column]
    private ?bool $isStarted = false;

    #[ORM\Column(nullable: true)]
    private ?int $currentVotingSecretIndex = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $currentVotingStartedAt = null;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->secrets = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->inviteCode = bin2hex(random_bytes(16));
        $this->currentState = self::STATE_WAITING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(bool $isPrivate): static
    {
        $this->isPrivate = $isPrivate;
        return $this;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->maxCapacity;
    }

    public function setMaxCapacity(int $maxCapacity): static
    {
        $this->maxCapacity = $maxCapacity;
        return $this;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(User $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }
        return $this;
    }

    public function removePlayer(User $player): static
    {
        $this->players->removeElement($player);
        return $this;
    }

    public function getSecrets(): Collection
    {
        return $this->secrets;
    }

    public function addSecret(Secret $secret): static
    {
        if (!$this->secrets->contains($secret)) {
            $this->secrets->add($secret);
            $secret->setRoom($this);
        }
        return $this;
    }

    public function removeSecret(Secret $secret): static
    {
        if ($this->secrets->removeElement($secret)) {
            if ($secret->getRoom() === $this) {
                $secret->setRoom(null);
            }
        }
        return $this;
    }

    public function getInviteCode(): ?string
    {
        return $this->inviteCode;
    }

    public function setInviteCode(string $inviteCode): static
    {
        $this->inviteCode = $inviteCode;
        return $this;
    }

    public function isStarted(): ?bool
    {
        return $this->isStarted;
    }

    public function setIsStarted(bool $isStarted): static
    {
        $this->isStarted = $isStarted;
        return $this;
    }


    public function getCurrentState(): ?string
    {
        return $this->currentState;
    }

    public function setCurrentState(string $currentState): static
    {
        if (
            !in_array($currentState, [
            self::STATE_WAITING,
            self::STATE_STARTING,
            self::STATE_PLAYING,
            self::STATE_VOTING,
            self::STATE_FINISHED
            ])
        ) {
            throw new \InvalidArgumentException('Invalid state');
        }

        $this->currentState = $currentState;
        return $this;
    }

    public function getStartingPhaseStartedAt(): ?\DateTimeImmutable
    {
        return $this->startingPhaseStartedAt;
    }

    public function setStartingPhaseStartedAt(?\DateTimeImmutable $startingPhaseStartedAt): static
    {
        $this->startingPhaseStartedAt = $startingPhaseStartedAt;
        return $this;
    }

    public function getPlayingPhaseStartedAt(): ?\DateTimeImmutable
    {
        return $this->playingPhaseStartedAt;
    }

    public function setPlayingPhaseStartedAt(?\DateTimeImmutable $playingPhaseStartedAt): static
    {
        $this->playingPhaseStartedAt = $playingPhaseStartedAt;
        return $this;
    }

    public function getVotingPhaseStartedAt(): ?\DateTimeImmutable
    {
        return $this->votingPhaseStartedAt;
    }

    public function setVotingPhaseStartedAt(?\DateTimeImmutable $votingPhaseStartedAt): static
    {
        $this->votingPhaseStartedAt = $votingPhaseStartedAt;
        return $this;
    }

    public function getRemainingTime(): ?int
    {
        $now = new \DateTimeImmutable();

        switch ($this->currentState) {
            case self::STATE_STARTING:
                if (!$this->startingPhaseStartedAt) {
                    return null;
                }
                $elapsed = $now->getTimestamp() - $this->startingPhaseStartedAt->getTimestamp();
                return max(0, 10 - $elapsed);

            case self::STATE_PLAYING:
                if (!$this->playingPhaseStartedAt) {
                    return null;
                }
                $elapsed = $now->getTimestamp() - $this->playingPhaseStartedAt->getTimestamp();
                return max(0, 30 - $elapsed);

            default:
                return null;
        }
    }

    public function shouldMoveToNextPhase(): bool
    {
        $remainingTime = $this->getRemainingTime();
        return $remainingTime !== null && $remainingTime <= 0;
    }

    public function getCurrentVotingSecretIndex(): ?int
    {
        return $this->currentVotingSecretIndex;
    }

    public function setCurrentVotingSecretIndex(?int $index): static
    {
        $this->currentVotingSecretIndex = $index;
        return $this;
    }

    public function getCurrentVotingStartedAt(): ?\DateTimeImmutable
    {
        return $this->currentVotingStartedAt;
    }

    public function setCurrentVotingStartedAt(?\DateTimeImmutable $startedAt): static
    {
        $this->currentVotingStartedAt = $startedAt;
        return $this;
    }

    public function getCurrentVotingSecret(): ?Secret
    {
        if ($this->currentVotingSecretIndex === null) {
            return null;
        }

        $secrets = $this->getSecrets()->toArray();
        return $secrets[$this->currentVotingSecretIndex] ?? null;
    }

    public function getRemainingVotingTime(): ?int
    {
        if ($this->currentVotingStartedAt === null || $this->currentState !== self::STATE_VOTING) {
            return null;
        }

        $now = new \DateTimeImmutable();
        $elapsed = $now->getTimestamp() - $this->currentVotingStartedAt->getTimestamp();
        return max(0, 20 - $elapsed);
    }

    public function shouldMoveToNextSecret(): bool
    {
        if ($this->getRemainingVotingTime() === 0) {
            return true;
        }

        $currentSecret = $this->getCurrentVotingSecret();
        if (!$currentSecret) {
            return false;
        }

        $totalPlayers = $this->getPlayers()->count();
        $totalVotesForCurrentSecret = $currentSecret->getVotes()->count();

        return $totalVotesForCurrentSecret >= $totalPlayers;
    }
}
