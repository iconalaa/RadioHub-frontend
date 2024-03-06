<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo ;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $id_user = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Put State Please!")]
    private ?bool $etat_rec = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    //#[Gedmo\Timestampable(on: "create")]
    private ?\DateTimeInterface $date_rec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Put Desc_Rec Please!")]
    private ?string $desc_rec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Put Type Please!")]

    private ?string $type_rec = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
   
    private ?Reponse $reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?string
    {
        return $this->id_user;
    }

    public function setIdUser(string $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function isEtatRec(): ?bool
    {
        return $this->etat_rec;
    }

    public function setEtatRec(bool $etat_rec): static
    {
        $this->etat_rec = $etat_rec;

        return $this;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->date_rec;
    }

    public function setDateRec(\DateTimeInterface $date_rec): static
    {
        $this->date_rec = $date_rec;

        return $this;
    }

    public function getDescRec(): ?string
    {
        return $this->desc_rec;
    }

    public function setDescRec(string $desc_rec): static
    {
        $this->desc_rec = $desc_rec;

        return $this;
    }

    public function getTypeRec(): ?string
    {
        return $this->type_rec;
    }

    public function setTypeRec(string $type_rec): static
    {
        $this->type_rec = $type_rec;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }
}
