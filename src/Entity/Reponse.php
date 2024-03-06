<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Put Desc_Rec Please!")]
    private ?string $desc_rep = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank (message:"Put date Please!")]
    private ?\DateTimeInterface $date_rep = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescRep(): ?string
    {
        return $this->desc_rep;
    }

    public function setDescRep(string $desc_rep): static
    {
        $this->desc_rep = $desc_rep;

        return $this;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->date_rep;
    }

    public function setDateRep(\DateTimeInterface $date_rep): static
    {
        $this->date_rep = $date_rep;

        return $this;
    }
    public function __toString():string{
        return $this->getDescRep();
    }
    
}
