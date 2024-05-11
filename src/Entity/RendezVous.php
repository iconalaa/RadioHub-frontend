<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateRV = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Put the exam type wanted Please!")]
    private ?string $typeExam = null;

    #[ORM\ManyToOne(inversedBy: 'rendezvous')]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'rendezVous')]
    #[JoinColumn(nullable: false)]
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRV(): ?\DateTimeInterface
    {
        return $this->dateRV;
    }

    public function setDateRV(?\DateTimeInterface $dateRV): static
    {
        $this->dateRV = $dateRV;

        return $this;
    }

    public function getTypeExam(): ?string
    {
        return $this->typeExam;
    }

    public function setTypeExam(string $typeExam): static
    {
        $this->typeExam = $typeExam;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;

        return $this;
    }



    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
