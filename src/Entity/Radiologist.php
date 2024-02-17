<?php

namespace App\Entity;

use App\Repository\RadiologistRepository;
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

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

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
}
