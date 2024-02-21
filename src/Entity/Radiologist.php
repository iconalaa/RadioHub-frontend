<?php

namespace App\Entity;

use App\Repository\RadiologistRepository;
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
    #[Assert\NotBlank(message: "Write your Mat Cnom")]
    private ?string $mat_cnom = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[Assert\NotBlank(message: "Select A User")]
    private ?User $user = null;


    #[ORM\OneToMany(mappedBy: 'radiologist', targetEntity: Images::class,cascade: ['remove'] )] 
    private Collection $id_radio;


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

    public function __construct()
    {
        $this->id_radio = new ArrayCollection();
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
     * @return Collection<int, Images>
     */
    public function getIdRadio(): Collection
    {
        return $this->id_radio;
    }

    public function addIdRadio(Images $idRadio): static
    {
        if (!$this->id_radio->contains($idRadio)) {
            $this->id_radio->add($idRadio);
            $idRadio->setRadiologist($this);
        }

        return $this;
    }
    

    public function removeIdRadio(Images $idRadio): static
    {
        if ($this->id_radio->removeElement($idRadio)) {
            // set the owning side to null (unless already changed)
            if ($idRadio->getRadiologist() === $this) {
                $idRadio->setRadiologist(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        if ($this->user) {
            return $this->user->getName() . ' ' . $this->user->getLastname();
        }
        
        
    }
}
