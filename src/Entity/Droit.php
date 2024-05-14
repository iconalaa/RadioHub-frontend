<?php

namespace App\Entity;

use App\Repository\DroitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DroitRepository::class)]
class Droit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\ManyToOne(inversedBy: 'droits')]
        private ?User $radioloqist = null;

    #[ORM\ManyToOne(inversedBy: 'droits')]
    private ?Image $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getRadioloqist(): ?User
    {
        return $this->radioloqist;
    }

    public function setRadioloqist(?User $radioloqist): static
    {
        $this->radioloqist = $radioloqist;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }
}
