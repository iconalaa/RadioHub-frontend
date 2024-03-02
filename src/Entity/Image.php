<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]

    #[Assert\NotBlank(message: "This field cannot be blank.")]
    #[Assert\Length(min: 3, minMessage: "Your last name must contain at least {{ limit }} characters.")]

    private ?string $bodypart;

    #[ORM\Column(length: 255)]

    private ?string $filename = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Range(min: "-5 years", minMessage: "You must be at least 5 before years to aquisition of the image ",)]
    #[Assert\NotBlank(message: "This field cannot be blank.")]

    private ?\DateTimeInterface $aquisationDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateajout = null;



    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Radiologist $radiologist = null;

    #[ORM\OneToMany(mappedBy: 'image', targetEntity: Droit::class, orphanRemoval: true)]

    private Collection $droits;

    #[ORM\ManyToOne(inversedBy: 'image')]
    #[Assert\NotBlank(message: "the patient cannot be blank.")]

    private ?Patient $patient = null;

    #[ORM\OneToMany(mappedBy: 'image', targetEntity: Interpretation::class, orphanRemoval: true)]
    private Collection $interpretations;

    public function __construct()
    {
        $this->droits = new ArrayCollection();
        $this->interpretations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBodypart(): ?string
    {
        return $this->bodypart;
    }

    public function setBodypart(?string $bodypart): static
    {
        $this->bodypart = $bodypart;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAquisationDate(): ?\DateTimeInterface
    {
        return $this->aquisationDate;
    }

    public function setAquisationDate(?\DateTimeInterface $aquisationDate): static
    {
        $this->aquisationDate = $aquisationDate;

        return $this;
    }

    public function getDateajout(): ?\DateTimeInterface
    {
        return $this->dateajout;
    }

    public function setDateajout(\DateTimeInterface $dateajout): static
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    public function getRadiologist(): ?Radiologist
    {
        return $this->radiologist;
    }

    public function setRadiologist(?Radiologist $radiologist): static
    {
        $this->radiologist = $radiologist;

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
            $droit->setImage($this);
        }

        return $this;
    }

    public function removeDroit(Droit $droit): static
    {
        if ($this->droits->removeElement($droit)) {
            // set the owning side to null (unless already changed)
            if ($droit->getImage() === $this) {
                $droit->setImage(null);
            }
        }

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
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
            $interpretation->setImage($this);
        }

        return $this;
    }

    public function removeInterpretation(Interpretation $interpretation): static
    {
        if ($this->interpretations->removeElement($interpretation)) {
            // set the owning side to null (unless already changed)
            if ($interpretation->getImage() === $this) {
                $interpretation->setImage(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getId();
    }
}
