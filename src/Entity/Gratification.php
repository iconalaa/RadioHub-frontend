<?php

namespace App\Entity;

use App\Repository\GratificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: GratificationRepository::class)]
class Gratification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,  nullable:true)]
    // #[Assert\Date(message: 'Veillez choisir une date acceptable')]
    #"[Assert\Type("date")]
    ##[Assert\NotNull(message: 'la date est obligatoire')]
    private ?\DateTimeInterface $Date_grat ;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:'le titre doit etre précisé')]
    private ?string $Titre_Grat = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message:'le titre doit etre précisé')]
    private ?string $Desc_Grat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:'Veuillez choisir une option')]
    private ?string $Type_Grat = null;

    #[ORM\ManyToOne(inversedBy: 'gratifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Donateur $ID_Donateur = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateGrat(): ?\DateTimeInterface
    {
        return $this->Date_grat;
    }

    public function setDateGrat(?\DateTimeInterface $Date_grat): static
    {
        $this->Date_grat = $Date_grat;

        return $this;
    }

    public function getTitreGrat(): ?string
    {
        return $this->Titre_Grat;
    }

    public function setTitreGrat(?string $Titre_Grat): static
    {
        $this->Titre_Grat = $Titre_Grat;

        return $this;
    }

    public function getDescGrat(): ?string
    {
        return $this->Desc_Grat;
    }

    public function setDescGrat(?string $Desc_Grat): static
    {
        $this->Desc_Grat = $Desc_Grat;

        return $this;
    }

    public function getTypeGrat(): ?string
    {
        return $this->Type_Grat;
    }

    public function setTypeGrat(?string $Type_Grat): static
    {
        $this->Type_Grat = $Type_Grat;

        return $this;
    }

    public function getIDDonateur(): ?Donateur
    {
        return $this->ID_Donateur;
    }

    public function setIDDonateur(?Donateur $ID_Donateur): static
    {
        $this->ID_Donateur = $ID_Donateur;

        return $this;
    }
/*
    public function __toString()
    {
        return (string)$this->getDateGrat();
    }

   */ 
}
