<?php

namespace App\Entity;

use App\Repository\CategoryLivreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryLivreRepository::class)]
class CategoryLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $DescriptionLivre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDescriptionLivre(): ?string
    {
        return $this->DescriptionLivre;
    }

    public function setDescriptionLivre(string $DescriptionLivre): static
    {
        $this->DescriptionLivre = $DescriptionLivre;

        return $this;
    }
}
