<?php

namespace App\Entity;

use App\Repository\ReservationLivreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationLivreRepository::class)]
class ReservationLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateReservationLivre = null;

    #[ORM\Column(length: 255)]
    private ?string $StatutReservationLivre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateExpirationReservationLivre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservationLivre(): ?\DateTimeInterface
    {
        return $this->DateReservationLivre;
    }

    public function setDateReservationLivre(\DateTimeInterface $DateReservationLivre): static
    {
        $this->DateReservationLivre = $DateReservationLivre;

        return $this;
    }

    public function getStatutReservationLivre(): ?string
    {
        return $this->StatutReservationLivre;
    }

    public function setStatutReservationLivre(string $StatutReservationLivre): static
    {
        $this->StatutReservationLivre = $StatutReservationLivre;

        return $this;
    }

    public function getDateExpirationReservationLivre(): ?\DateTimeInterface
    {
        return $this->DateExpirationReservationLivre;
    }

    public function setDateExpirationReservationLivre(\DateTimeInterface $DateExpirationReservationLivre): static
    {
        $this->DateExpirationReservationLivre = $DateExpirationReservationLivre;

        return $this;
    }
}
