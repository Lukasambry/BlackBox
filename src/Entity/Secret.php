<?php

namespace App\Entity;

use App\Repository\SecretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecretRepository::class)]
class Secret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $votes_up = null;

    #[ORM\Column(nullable: true)]
    private ?int $votes_down = null;

    #[ORM\ManyToOne(inversedBy: 'secrets')]
    #[ORM\JoinColumn(name: "user_id", nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'secrets')]
    #[ORM\JoinColumn(name: "room_id", nullable: true)]
    private ?Room $room = null;

    #[ORM\OneToMany(targetEntity: Vote::class, mappedBy: 'secret')]
    private Collection $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getVotesUp(): ?int
    {
        return $this->votes_up;
    }

    public function setVotesUp(?int $votes_up): static
    {
        $this->votes_up = $votes_up;

        return $this;
    }

    public function getVotesDown(): ?int
    {
        return $this->votes_down;
    }

    public function setVotesDown(?int $votes_down): static
    {
        $this->votes_down = $votes_down;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setSecret($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            if ($vote->getSecret() === $this) {
                $vote->setSecret(null);
            }
        }

        return $this;
    }
}
