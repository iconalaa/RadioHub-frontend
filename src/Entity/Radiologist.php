<?php

namespace App\Entity;

use App\Repository\RadiologistRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RadiologistRepository::class)]
class Radiologist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Write your Mat Cnom !")]

    private ?string $mat_cnom = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dispo = null;


    #[ORM\OneToMany(mappedBy: 'radiologist', targetEntity: Image::class)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'radioloqist', targetEntity: Droit::class)]
    private Collection $droits;

    #[ORM\OneToMany(mappedBy: 'radiologist', targetEntity: Interpretation::class)]
    private Collection $interpretations;


    public function __construct()
    {

        $this->patients = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->droits = new ArrayCollection();
        $this->interpretations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatCnom(): ?string
    {
        return $this->mat_cnom;
    }

    public function setMatCnom(string $mat_cnom): static
    {
        $this->mat_cnom = $mat_cnom;

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

    public function isDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(?bool $dispo): static
    {
        $this->dispo = $dispo;

        return $this;
    }

    /**
     * @return Collection<int, Patient>
     */

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setRadiologist($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRadiologist() === $this) {
                $image->setRadiologist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Droit>
     */
    public function getDroits(): Collection
    {
        return $this->droits;
    }

    public function addDroit(Droit $droit): static
    {
        if (!$this->droits->contains($droit)) {
            $this->droits->add($droit);
            $droit->setRadioloqist($this);
        }

        return $this;
    }

    public function removeDroit(Droit $droit): static
    {
        if ($this->droits->removeElement($droit)) {
            // set the owning side to null (unless already changed)
            if ($droit->getRadioloqist() === $this) {
                $droit->setRadioloqist(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->getUser()->getName();
    }

    /**
     * @return Collection<int, Interpretation>
     */
    public function getInterpretations(): Collection
    {
        return $this->interpretations;
    }

    public function addInterpretation(Interpretation $interpretation): static
    {
        if (!$this->interpretations->contains($interpretation)) {
            $this->interpretations->add($interpretation);
            $interpretation->setRadiologist($this);
        }

        return $this;
    }

    public function removeInterpretation(Interpretation $interpretation): static
    {
        if ($this->interpretations->removeElement($interpretation)) {
            // set the owning side to null (unless already changed)
            if ($interpretation->getRadiologist() === $this) {
                $interpretation->setRadiologist(null);
            }
        }

        return $this;
    }
}
