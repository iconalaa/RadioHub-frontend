<?php

namespace App\Entity;

use App\Repository\DonateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonateurRepository::class)]
class Donateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom_Donateur = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom_Donateur = null;

    #[ORM\Column(length: 255)]
    private ?string $Type_Donateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'L"adresse du courriel éléctronique  est obligatoire')]
    #[Assert\Email(message:'Veillez insérer une adresse email valide')]
    private ?string $Email = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $Telephone = null;

    #[ORM\OneToMany(mappedBy: 'ID_Donateur', targetEntity: Gratification::class)]
    private Collection $gratifications;

    public function __construct()
    {
        $this->gratifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDonateur(): ?string
    {
        return $this->Nom_Donateur;
    }

    public function setNomDonateur(string $Nom_Donateur): static
    {
        $this->Nom_Donateur = $Nom_Donateur;

        return $this;
    }

    public function getPrenomDonateur(): ?string
    {
        return $this->Prenom_Donateur;
    }

    public function setPrenomDonateur(string $Prenom_Donateur): static
    {
        $this->Prenom_Donateur = $Prenom_Donateur;

        return $this;
    }

    public function getTypeDonateur(): ?string
    {
        return $this->Type_Donateur;
    }

    public function setTypeDonateur(string $Type_Donateur): static
    {
        $this->Type_Donateur = $Type_Donateur;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->Telephone;
    }

    public function setTelephone(string $Telephone): static
    {
        $this->Telephone = $Telephone;

        return $this;
    }

    /**
     * @return Collection<int, Gratification>
     */
    public function getGratifications(): Collection
    {
        return $this->gratifications;
    }

    public function addGratification(Gratification $gratification): static
    {
        if (!$this->gratifications->contains($gratification)) {
            $this->gratifications->add($gratification);
            $gratification->setIDDonateur($this);
        }

        return $this;
    }

    public function removeGratification(Gratification $gratification): static
    {
        if ($this->gratifications->removeElement($gratification)) {
            // set the owning side to null (unless already changed)
            if ($gratification->getIDDonateur() === $this) {
                $gratification->setIDDonateur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }
}
