<?php

namespace App\Entity;

use App\Repository\CompteRenduRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteRenduRepository::class)]
class CompteRendu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255 , nullable: true) ]
    private ?string $interpretationMed = null;


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;


    #[ORM\ManyToOne(inversedBy: 'compteRendus')]
    private ?Doctor $id_doctor = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Images $id_image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $interpretation_rad = null;

    #[ORM\Column]
    private ?bool $isEdited = false;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInterpretationMed(): ?string
    {
        return $this->interpretationMed;
    }

    public function setInterpretationMed(string $interpretationMed): static
    {
        $this->interpretationMed = $interpretationMed;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdDoctor(): ?Doctor
    {
        return $this->id_doctor;
    }

    public function setIdDoctor(?Doctor $id_doctor): static
    {
        $this->id_doctor = $id_doctor;

        return $this;
    }

    public function getIdImage(): ?Images
    {
        return $this->id_image;
    }
    

    public function setIdImage(?Images $id_image): static
    {
        $this->id_image = $id_image;

        return $this;
    }
    public function __toString()
    {
        return $this->id_image;
    }

    public function getInterpretationRad(): ?string
    {
        return $this->interpretation_rad;
    }

    public function setInterpretationRad(?string $interpretation_rad): static
    {
        $this->interpretation_rad = $interpretation_rad;

        return $this;
    }

    public function getIsEdited(): ?bool
    {
        return $this->isEdited;
    }

    public function setIsEdited(bool $isEdited): self
    {
        $this->isEdited = $isEdited;

        return $this;
    }

  
    
}
