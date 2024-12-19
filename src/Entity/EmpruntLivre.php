<?php

namespace App\Entity;

use App\Repository\EmpruntLivreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntLivreRepository::class)]
class EmpruntLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateEmpruntLivre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateRetourLivre = null;

    #[ORM\Column(length: 255)]
    private ?string $StatutLivre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmpruntLivre(): ?\DateTimeInterface
    {
        return $this->DateEmpruntLivre;
    }

    public function setDateEmpruntLivre(\DateTimeInterface $DateEmpruntLivre): static
    {
        $this->DateEmpruntLivre = $DateEmpruntLivre;

        return $this;
    }

    public function getDateRetourLivre(): ?\DateTimeInterface
    {
        return $this->DateRetourLivre;
    }

    public function setDateRetourLivre(\DateTimeInterface $DateRetourLivre): static
    {
        $this->DateRetourLivre = $DateRetourLivre;

        return $this;
    }

    public function getStatutLivre(): ?string
    {
        return $this->StatutLivre;
    }

    public function setStatutLivre(string $StatutLivre): static
    {
        $this->StatutLivre = $StatutLivre;

        return $this;
    }
}
