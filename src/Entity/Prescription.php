<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'string')]
    private string $signatureFilename;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?CompteRendu $compterendu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSignatureFilename(): string
    {
        return $this->signatureFilename;
    }

    public function setSignatureFilename(string $signatureFilename): self
    {
        $this->signatureFilename = $signatureFilename;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate($date): static
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        }

        $this->date = $date;

        return $this;
    }

    public function getCompterendu(): ?CompteRendu
    {
        return $this->compterendu;
    }

    public function setCompterendu(?CompteRendu $compterendu): static
    {
        $this->compterendu = $compterendu;

        return $this;
    }
}
