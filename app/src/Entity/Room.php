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
    private ?int $capacityMaximum = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Workshop::class)]
    private Collection $workshops;

    public function __construct()
    {
        $this->workshops = new ArrayCollection();
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

    public function getCapacityMaximum(): ?int
    {
        return $this->capacityMaximum;
    }

    public function setCapacityMaximum(int $capacityMaximum): static
    {
        $this->capacityMaximum = $capacityMaximum;

        return $this;
    }

    /**
     * @return Collection<int, Workshop>
     */
    public function getJobs(): Collection
    {
        return $this->workshops;
    }

    public function addJob(Workshop $job): static
    {
        if (!$this->workshops->contains($job)) {
            $this->workshops->add($job);
            $job->setRoom($this);
        }

        return $this;
    }

    public function removeJob(Workshop $job): static
    {
        if ($this->workshops->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getRoom() === $this) {
                $job->setRoom(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
