<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
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
    private ?\DateTimeImmutable $created_at;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at;

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

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->secrets = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->inviteCode = bin2hex(random_bytes(16));
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

    /**
     * @return Collection<int, Secret>
     */
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
}