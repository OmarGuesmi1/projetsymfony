<?php

namespace App\Entity;

use App\Repository\ReservationEvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationEvenementRepository::class)]
class ReservationEvenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDebutRE = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateFinRE = null;

    #[ORM\Column(length: 255)]
    private ?string $ClassReservationEvent = null;

    #[ORM\Column]
    private ?bool $StatutReservationEvent = null;

    #[ORM\OneToOne(inversedBy: 'ReservationEvenement', cascade: ['persist', 'remove'])]
    private ?Notificationevent $Notificationevent = null;

    /**
     * @var Collection<int, Evenement>
     */
    #[ORM\OneToMany(targetEntity: Evenement::class, mappedBy: 'ReservationEvenement')]
    private Collection $evenements;

    /**
     * @var Collection<int, Participant>
     */
    #[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'reservationEvenement')]
    private Collection $participants;

    public function __construct()
    {
        // Initialisation des collections
        $this->evenements = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutRE(): ?\DateTimeInterface
    {
        return $this->DateDebutRE;
    }

    public function setDateDebutRE(\DateTimeInterface $DateDebutRE): static
    {
        $this->DateDebutRE = $DateDebutRE;
        return $this;
    }

    public function getDateFinRE(): ?\DateTimeInterface
    {
        return $this->DateFinRE;
    }

    public function setDateFinRE(\DateTimeInterface $DateFinRE): static
    {
        $this->DateFinRE = $DateFinRE;
        return $this;
    }

    public function getClassReservationEvent(): ?string
    {
        return $this->ClassReservationEvent;
    }

    public function setClassReservationEvent(string $ClassReservationEvent): static
    {
        $this->ClassReservationEvent = $ClassReservationEvent;
        return $this;
    }

    public function isStatutReservationEvent(): ?bool
    {
        return $this->StatutReservationEvent;
    }

    public function setStatutReservationEvent(bool $StatutReservationEvent): static
    {
        $this->StatutReservationEvent = $StatutReservationEvent;
        return $this;
    }

    public function getNotificationevent(): ?Notificationevent
    {
        return $this->Notificationevent;
    }

    public function setNotificationevent(?Notificationevent $Notificationevent): static
    {
        $this->Notificationevent = $Notificationevent;
        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setReservationEvenement($this); // Mise à jour de la relation inverse
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            // Mise à jour de la relation inverse si nécessaire
            if ($participant->getReservationEvenement() === $this) {
                $participant->setReservationEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setReservationEvenement($this); // Mise à jour de la relation inverse
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            // Mise à jour de la relation inverse si nécessaire
            if ($evenement->getReservationEvenement() === $this) {
                $evenement->setReservationEvenement(null);
            }
        }

        return $this;
    }
}
