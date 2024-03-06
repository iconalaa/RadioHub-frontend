<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   
    private ?int $id = null;

    //#[ORM\Column(length: 255)]
    //#[Assert\NotBlank(message:"Put State Please!")]
    //private ?string $statusRV = null;
    #[ORM\Column]
    #[Assert\NotBlank(message:"enter date Please!")]
    private ?\DateTime $dateRV = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Put the exam type wanted Please!")]
    private ?string $typeExam = null;

    #[ORM\ManyToOne(inversedBy: 'rendezvous')]
    #[Assert\NotBlank]
    private ?Salle $salle = null;
    #[Assert\NotBlank(message:"Put your name Please!")]
    #[ORM\Column(length: 255)]
    private ?string $nomPatient = null;
    #[Assert\NotBlank(message:"Put your lastname Please!")]
    #[ORM\Column(length: 255)]
    private ?string $prenomPatient = null;
    #[Assert\NotBlank(message:"Put your mail Please!")]
    #[ORM\Column(length: 255)]

     
    #[Assert\Email(message : "The email '{{ value }}' is not a valid email.")]
     
    
    private ?string $mailPatient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

   /// public function getStatusRV(): ?string
    //{
      //  return $this->statusRV;
    //}

   // public function setStatusRV(string $statusRV): static
    //{
      //  $this->statusRV = $statusRV;

        //return $this;
    //}

    public function getDateRV(): ?\DateTimeInterface
    {
        return $this->dateRV;
    }

    public function setDateRV(\DateTimeInterface $dateRV): static
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

    public function getNomPatient(): ?string
    {
        return $this->nomPatient;
    }

    public function setNomPatient(string $nomPatient): static
    {
        $this->nomPatient = $nomPatient;

        return $this;
    }

    public function getPrenomPatient(): ?string
    {
        return $this->prenomPatient;
    }

    public function setPrenomPatient(string $prenomPatient): static
    {
        $this->prenomPatient = $prenomPatient;

        return $this;
    }

    public function getMailPatient(): ?string
    {
        return $this->mailPatient;
    }

    public function setMailPatient(string $mailPatient): static
    {
        $this->mailPatient = $mailPatient;

        return $this;
    }
}
