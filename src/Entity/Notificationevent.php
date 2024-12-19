<?php

namespace App\Entity;

use App\Repository\NotificationeventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationeventRepository::class)]
class Notificationevent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $messageevent = null;

    #[ORM\OneToOne(mappedBy: 'Notificationevent', cascade: ['persist', 'remove'])]
    private ?ReservationEvenement $ReservationEvenement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageevent(): ?string
    {
        return $this->messageevent;
    }

    public function setMessageevent(string $messageevent): static
    {
        $this->messageevent = $messageevent;

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
