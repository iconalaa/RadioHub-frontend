<?php

namespace App\Entity;

use App\Repository\InterpretationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InterpretationRepository::class)]
class Interpretation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "This field cannot be blank.")]
    #[Assert\Regex(
        pattern: '/(\b[a-zA-Z]+\b\s*){4,}/',
        message: 'This field must contain at least 4 words.'
    )]
    private ?string $interpretation = null;

    #[ORM\Column(length: 255)]
    private ?string $sendat = null;

    #[ORM\ManyToOne(inversedBy: 'interpretations')]
    private ?User $radiologist = null;

    #[ORM\ManyToOne(inversedBy: 'interpretations')]
    private ?Image $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "This field cannot be blank.")]

    private ?string $urgency = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "This field cannot be blank.")]
   
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInterpretation(): ?string
    {
        return $this->interpretation;
    }

    public function setInterpretation(?string $interpretation): static
    {
        $this->interpretation = $interpretation;

        return $this;
    }

    public function getSendat(): ?string
    {
        return $this->sendat;
    }

    public function setSendat(string $sendat): static
    {
        $this->sendat = $sendat;

        return $this;
    }

    public function getRadiologist(): ?User
    {
        return $this->radiologist;
    }

    public function setRadiologist(?User $radiologist): static
    {
        $this->radiologist = $radiologist;

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

    public function getUrgency(): ?string
    {
        return $this->urgency;
    }

    public function setUrgency(string $urgency): static
    {
        $this->urgency = $urgency;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
