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

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Report::class)]
    private Collection $Reports;


    #[ORM\OneToOne(cascade: ['persist'])]
    #[Assert\NotBlank(message: "you should Select a User !")]
    private ?User $user = null;

    public function __construct()
    {
        $this->Reports = new ArrayCollection();
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
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->Reports;
    }
    public function __toString()
    {
        if ($this->user) {
            return $this->user->getName() . ' ' . $this->user->getLastname();
        }
        
        return 'Doctor';
    }

    public function addReport(Report $Report): static
    {
        if (!$this->Reports->contains($Report)) {
            $this->Reports->add($Report);
            $Report->setDoctor($this);
        }

        return $this;
    }

    public function removeReport(Report $Report): static
    {
        if ($this->Reports->removeElement($Report)) {
            // set the owning side to null (unless already changed)
            if ($Report->getDoctor() === $this) {
                $Report->setDoctor(null);
            }
        }

        return $this;
    }
}