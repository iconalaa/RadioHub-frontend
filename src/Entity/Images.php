<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $owner = null;

    #[ORM\Column(length: 255)]
    private ?string $guest = null;

    #[ORM\ManyToOne(inversedBy: 'id_radio')]
    private ?Radiologist $radiologist = null;

    #[ORM\ManyToOne(inversedBy: 'id_patient')]
    private ?Patient $patient = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getGuest(): ?string
    {
        return $this->guest;
    }

    public function setGuest(string $guest): static
    {
        $this->guest = $guest;

        return $this;
    }

    public function getRadiologist(): ?Radiologist
    {
        return $this->radiologist;
    }

    public function setRadiologist(?Radiologist $radiologist): static
    {
        $this->radiologist = $radiologist;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }
    public function __toString()
    {
        return $this->radiologist;
    }

    
}
