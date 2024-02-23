<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "you should tell us your Medical problem")]
    private ?string $cas_med = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "you should give CNUM Number")]
    private ?string $n_cnam = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "you should Give Your Assurance Info")]
    private ?string $assurance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "you should Write your assurance Nubmer")]
    private ?string $num_assurance = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Image::class)]
    private Collection $image;

    public function __construct()
    {
        $this->image = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCasMed(): ?string
    {
        return $this->cas_med;
    }

    public function setCasMed(string $cas_med): static
    {
        $this->cas_med = $cas_med;

        return $this;
    }

    public function getNCnam(): ?string
    {
        return $this->n_cnam;
    }

    public function setNCnam(string $n_cnam): static
    {
        $this->n_cnam = $n_cnam;

        return $this;
    }

    public function getAssurance(): ?string
    {
        return $this->assurance;
    }

    public function setAssurance(string $assurance): static
    {
        $this->assurance = $assurance;

        return $this;
    }

    public function getNumAssurance(): ?string
    {
        return $this->num_assurance;
    }

    public function setNumAssurance(string $num_assurance): static
    {
        $this->num_assurance = $num_assurance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }





    /**
     * @return Collection<int, Image>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setPatient($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPatient() === $this) {
                $image->setPatient(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return  $this->getUser()->getName();
    }
}
