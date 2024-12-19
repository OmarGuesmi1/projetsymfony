<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TitreLivre = null;

    #[ORM\Column(length: 255)]
    private ?string $AuteurLivre = null;

    #[ORM\Column(length: 255)]
    private ?string $IsbnLivre = null;

    #[ORM\Column]
    private ?int $NombreExemplaireLivre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $AnneePublicationLivre = null;

    #[ORM\Column(length: 255)]
    private ?string $ImageLivre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreLivre(): ?string
    {
        return $this->TitreLivre;
    }

    public function setTitreLivre(string $TitreLivre): static
    {
        $this->TitreLivre = $TitreLivre;

        return $this;
    }

    public function getAuteurLivre(): ?string
    {
        return $this->AuteurLivre;
    }

    public function setAuteurLivre(string $AuteurLivre): static
    {
        $this->AuteurLivre = $AuteurLivre;

        return $this;
    }

    public function getIsbnLivre(): ?string
    {
        return $this->IsbnLivre;
    }

    public function setIsbnLivre(string $IsbnLivre): static
    {
        $this->IsbnLivre = $IsbnLivre;

        return $this;
    }

    public function getNombreExemplaireLivre(): ?int
    {
        return $this->NombreExemplaireLivre;
    }

    public function setNombreExemplaireLivre(int $NombreExemplaireLivre): static
    {
        $this->NombreExemplaireLivre = $NombreExemplaireLivre;

        return $this;
    }

    public function getAnneePublicationLivre(): ?\DateTimeInterface
    {
        return $this->AnneePublicationLivre;
    }

    public function setAnneePublicationLivre(\DateTimeInterface $AnneePublicationLivre): static
    {
        $this->AnneePublicationLivre = $AnneePublicationLivre;

        return $this;
    }

    public function getImageLivre(): ?string
    {
        return $this->ImageLivre;
    }

    public function setImageLivre(string $ImageLivre): static
    {
        $this->ImageLivre = $ImageLivre;

        return $this;
    }
}
