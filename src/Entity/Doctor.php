<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "you should write your doctor Matricule !")]
    private ?string $matricule = null;

    #[ORM\OneToMany(mappedBy: 'id_doctor', targetEntity: CompteRendu::class)]
    private Collection $compteRendus;


    #[ORM\OneToOne(cascade: ['persist'])]
    #[Assert\NotBlank(message: "you should Select a User !")]
    private ?User $user = null;

    public function __construct()
    {
        $this->compteRendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    

    /**
     * @return Collection<int, CompteRendu>
     */
    public function getCompteRendus(): Collection
    {
        return $this->compteRendus;
    }
    public function __toString()
    {
        if ($this->user) {
            return $this->user->getName() . ' ' . $this->user->getLastname();
        }
        
        return 'Doctor';
    }

    public function addCompteRendu(CompteRendu $compteRendu): static
    {
        if (!$this->compteRendus->contains($compteRendu)) {
            $this->compteRendus->add($compteRendu);
            $compteRendu->setIdDoctor($this);
        }

        return $this;
    }

    public function removeCompteRendu(CompteRendu $compteRendu): static
    {
        if ($this->compteRendus->removeElement($compteRendu)) {
            // set the owning side to null (unless already changed)
            if ($compteRendu->getIdDoctor() === $this) {
                $compteRendu->setIdDoctor(null);
            }
        }

        return $this;
    }
}
