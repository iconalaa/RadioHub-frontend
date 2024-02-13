<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Images::class,cascade: ['remove'])]
    private Collection $id_patient;

    public function __construct()
    {
        $this->id_patient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getIdPatient(): Collection
    {
        return $this->id_patient;
    }

    public function addIdPatient(Images $idPatient): static
    {
        if (!$this->id_patient->contains($idPatient)) {
            $this->id_patient->add($idPatient);
            $idPatient->setPatient($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username;
    }

    public function removeIdPatient(Images $idPatient): static
    {
        if ($this->id_patient->removeElement($idPatient)) {
            // set the owning side to null (unless already changed)
            if ($idPatient->getPatient() === $this) {
                $idPatient->setPatient(null);
            }
        }

        return $this;
    }
}
