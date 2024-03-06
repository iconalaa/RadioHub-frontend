<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
 
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numSalle = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $numDep = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $etatSalle = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $TypeSalle = null;

    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: RendezVous::class)]
    
    private Collection $rendezvous;

    public function __construct()
    {
        $this->rendezvous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSalle(): ?string
    {
        return $this->numSalle;
    }

    public function setNumSalle(string $numSalle): static
    {
        $this->numSalle = $numSalle;

        return $this;
    }

    public function getNumDep(): ?string
    {
        return $this->numDep;
    }

    public function setNumDep(string $numDep): static
    {
        $this->numDep = $numDep;

        return $this;
    }

    public function getEtatSalle(): ?string
    {
        return $this->etatSalle;
    }

    public function setEtatSalle(string $etatSalle): static
    {
        $this->etatSalle = $etatSalle;

        return $this;
    }

    public function getTypeSalle(): ?string
    {
        return $this->TypeSalle;
    }

    public function setTypeSalle(string $TypeSalle): static
    {
        $this->TypeSalle = $TypeSalle;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezvous(): Collection
    {
        return $this->rendezvous;
    }

    public function addRendezvou(RendezVous $rendezvou): static
    {
        if (!$this->rendezvous->contains($rendezvou)) {
            $this->rendezvous->add($rendezvou);
            $rendezvou->setSalle($this);
        }

        return $this;
    }

    public function removeRendezvou(RendezVous $rendezvou): static
    {
        if ($this->rendezvous->removeElement($rendezvou)) {
            // set the owning side to null (unless already changed)
            if ($rendezvou->getSalle() === $this) {
                $rendezvou->setSalle(null);
            }
        }

        return $this;
    }
}
