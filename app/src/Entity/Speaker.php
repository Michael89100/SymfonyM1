<?php

namespace App\Entity;

use App\Repository\SpeakerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpeakerRepository::class)]
class Speaker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $socialEmail = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $resgistrationAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'speakers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Edition $edition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user?->getId();
    }

    public function getSocialEmail(): ?string
    {
        return $this->socialEmail;
    }

    public function setSocialEmail(string $socialEmail): static
    {
        $this->socialEmail = $socialEmail;

        return $this;
    }

    public function getResgistrationAt(): ?\DateTimeImmutable
    {
        return $this->resgistrationAt;
    }

    public function setResgistrationAt(?\DateTimeImmutable $resgistrationAt): static
    {
        $this->resgistrationAt = $resgistrationAt;

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

    public function getEdition(): ?Edition
    {
        return $this->edition;
    }

    public function setEdition(?Edition $edition): static
    {
        $this->edition = $edition;

        return $this;
    }
}
