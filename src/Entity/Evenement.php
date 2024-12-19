<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomEvenement = null;

    #[ORM\Column(length: 255)]
    private ?string $DescriptionEvenement = null;

    #[ORM\Column(length: 255)]
    private ?string $ImageEvenement = null;

    #[ORM\Column(length: 255)]
    private ?string $TypeEvenement = null;

    #[ORM\ManyToOne(inversedBy: 'evenements', cascade: ['remove'])]
private ?ReservationEvenement $ReservationEvenement = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvenement(): ?string
    {
        return $this->NomEvenement;
    }

    public function setNomEvenement(string $NomEvenement): static
    {
        $this->NomEvenement = $NomEvenement;

        return $this;
    }

    public function getDescriptionEvenement(): ?string
    {
        return $this->DescriptionEvenement;
    }

    public function setDescriptionEvenement(string $DescriptionEvenement): static
    {
        $this->DescriptionEvenement = $DescriptionEvenement;

        return $this;
    }

    public function getImageEvenement(): ?string
    {
        return $this->ImageEvenement;
    }

    public function setImageEvenement(string $ImageEvenement): static
    {
        $this->ImageEvenement = $ImageEvenement;

        return $this;
    }

    public function getTypeEvenement(): ?string
    {
        return $this->TypeEvenement;
    }

    public function setTypeEvenement(string $TypeEvenement): static
    {
        $this->TypeEvenement = $TypeEvenement;

        return $this;
    }

    public function getReservationEvenement(): ?ReservationEvenement
    {
        return $this->ReservationEvenement;
    }

    public function setReservationEvenement(?ReservationEvenement $ReservationEvenement): static
    {
        $this->ReservationEvenement = $ReservationEvenement;

        return $this;
    }
}
