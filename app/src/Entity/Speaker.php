<?php

namespace App\Entity;

use App\Repository\SpeakerRepository;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Workshop::class, mappedBy: 'speakers')]
    private Collection $workshops;

    #[ORM\ManyToOne(inversedBy: 'speakers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Workshop>
     */
    public function getWorkshops(): Collection
    {
        return $this->workshops;
    }

    public function addWorkshop(Workshop $workshop): static
    {
        if (!$this->workshops->contains($workshop)) {
            $this->workshops->add($workshop);
            $workshop->addSpeaker($this);
        }

        return $this;
    }

    public function removeWorkshop(Workshop $workshop): static
    {
        if ($this->workshops->removeElement($workshop)) {
            $workshop->removeSpeaker($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }


    public function getUserId(): ?int
    {
        return $this->user->getId();
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName();
    }
}
