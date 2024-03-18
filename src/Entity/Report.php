<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $interpretationMed = null;


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;


    #[ORM\ManyToOne(inversedBy: 'Reports')]
    private ?Doctor $doctor = null;


 
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $image = null;


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

    public function setInterpretationMed(?string $interpretationMed): static
    {
        $this->interpretationMed = $interpretationMed;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        // Convert string to DateTime object if necessary
        if (is_string($date)) {
            $date = new \DateTime($date);
        }

        $this->date = $date;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

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
    public function __toString()
    {
        return $this->image;
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