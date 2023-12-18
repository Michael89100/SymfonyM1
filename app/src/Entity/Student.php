<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $scoolEmail = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $registrationAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoolEmail(): ?string
    {
        return $this->scoolEmail;
    }

    public function setScoolEmail(string $scoolEmail): static
    {
        $this->scoolEmail = $scoolEmail;

        return $this;
    }

    public function getRegistrationAt(): ?\DateTimeImmutable
    {
        return $this->registrationAt;
    }

    public function setRegistrationAt(?\DateTimeImmutable $registrationAt): static
    {
        $this->registrationAt = $registrationAt;

        return $this;
    }
}
