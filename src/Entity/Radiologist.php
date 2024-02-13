<?php

namespace App\Entity;

use App\Repository\RadiologistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RadiologistRepository::class)]
class Radiologist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'radiologist', targetEntity: Images::class,cascade: ['remove'] )] 
    private Collection $id_radio;

    public function __construct()
    {
        $this->id_radio = new ArrayCollection();
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
    public function __toString()
    {
        return $this->username;
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
}
