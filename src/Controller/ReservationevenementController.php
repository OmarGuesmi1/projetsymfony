<?php

namespace App\Controller;

use App\Entity\ReservationEvenement;
use App\Entity\Participant;
use App\Entity\Notificationevent;
use App\Entity\Evenement;



use App\Form\ReservationEvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ParticipantRepository;

use App\Repository\ReservationEvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dompdf\Dompdf;

class ReservationevenementController extends AbstractController
{
    #[Route('/reservation/new', name: 'reservation_new')]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        $reservation = new ReservationEvenement();
    
        // Récupérer tous les participants et événements disponibles
        $participants = $entityManager->getRepository(Participant::class)->findAll();
        $evenements = $entityManager->getRepository(Evenement::class)->findAll();
    
        // Créer le formulaire
        $form = $this->createForm(ReservationEvenementType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les participants sélectionnés
            $selectedParticipantIds = $request->get('participants', []);
            foreach ($selectedParticipantIds as $participantId) {
                $participant = $entityManager->getRepository(Participant::class)->find($participantId);
                if ($participant) {
                    $reservation->addParticipant($participant); // Ajouter le participant à la réservation
                }
            }
    
            // Récupérer les événements sélectionnés
            $selectedEvenementIds = $request->get('evenements', []);
            foreach ($selectedEvenementIds as $evenementId) {
                $evenement = $entityManager->getRepository(Evenement::class)->find($evenementId);
                if ($evenement) {
                    $reservation->addEvenement($evenement); // Ajouter l'événement à la réservation
                }
            }
    
            // Définir le statut de la réservation
            $reservation->setStatutReservationEvent(false);
    
            // Sauvegarde dans la base de données
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            // Créer une notification
            $notification = new Notificationevent();
            $notification->setMessageevent("Une nouvelle réservation a été créée pour l'événement(s) : " . implode(', ', array_map(function($evenement) {
                return $evenement->getNomEvenement(); // Assurez-vous que 'getNomEvenement()' retourne le nom de l'événement
            }, $reservation->getEvenements()->toArray())));
    
            // Associer la notification à la réservation
            $reservation->setNotificationevent($notification);
    
            // Sauvegarder la notification
            $entityManager->persist($notification);
            $entityManager->flush();
    
            // Ajouter un message flash pour indiquer que la réservation a été créée
            $this->addFlash('success', 'Réservation créée avec succès et notification envoyée !');
    
            // Rediriger l'utilisateur vers l'index des réservations
            return $this->redirectToRoute('app_reservation_evenement_invalides');
        }
    
        // Envoyer les participants et événements au template
        return $this->render('enseignant/reservation_evenement/reservationecreate.html.twig', [
            'form' => $form->createView(),
            'participants' => $participants,
            'evenements' => $evenements,
        ]);
    }
    

    




    
    #[Route('/reservations-evenement-invalides', name: 'app_reservation_evenement_invalides')]
    public function reservationsInvalides(ReservationEvenementRepository $reservationEvenementRepository): Response
    {
        // Récupérer les réservations avec statut "false" (invalide)
        $reservationsInvalides = $reservationEvenementRepository->findBy(['StatutReservationEvent' => false]);

        return $this->render('enseignant/reservation_evenement/invalideevent.html.twig', [
            'reservations_invalides' => $reservationsInvalides,
        ]);
    }

    // Page pour afficher les réservations validées (statut = true)
    #[Route('/reservations-evenement-valides', name: 'app_reservation_evenement_valides')]
    public function reservationsValides(ReservationEvenementRepository $reservationEvenementRepository): Response
    {
        // Récupérer les réservations avec statut "true" (validé)
        $reservationsValides = $reservationEvenementRepository->findBy(['StatutReservationEvent' => true]);

        return $this->render('enseignant/reservation_evenement/valideevent.html.twig', [
            'reservations_valides' => $reservationsValides,
        ]);
    }


    
    #[Route('/reservation-evenement/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
public function edit(
    ReservationEvenement $reservation,
    Request $request,
    EntityManagerInterface $entityManager,
    ParticipantRepository $participantRepository,
    EvenementRepository $evenementRepository
): Response {
    $form = $this->createForm(ReservationEvenementType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion des participants
        $participantIds = $request->get('participants', []); // Récupérer les ID des participants sélectionnés
        $currentParticipants = $reservation->getParticipants(); // Participants existants

        // Supprimer les participants désélectionnés
        foreach ($currentParticipants as $participant) {
            if (!in_array($participant->getId(), $participantIds)) {
                $reservation->removeParticipant($participant);
            }
        }

        // Ajouter les participants sélectionnés
        foreach ($participantIds as $participantId) {
            $participant = $participantRepository->find($participantId);
            if ($participant && !$currentParticipants->contains($participant)) {
                $reservation->addParticipant($participant);
            }
        }

        // Gestion des événements
        $evenementIds = $request->get('evenements', []); // Récupérer les ID des événements sélectionnés
        $currentEvenements = $reservation->getEvenements(); // Événements existants

        // Supprimer les événements désélectionnés
        foreach ($currentEvenements as $evenement) {
            if (!in_array($evenement->getId(), $evenementIds)) {
                $reservation->removeEvenement($evenement);
            }
        }

        // Ajouter les événements sélectionnés
        foreach ($evenementIds as $evenementId) {
            $evenement = $evenementRepository->find($evenementId);
            if ($evenement && !$currentEvenements->contains($evenement)) {
                $reservation->addEvenement($evenement);
            }
        }

        // Sauvegarder les modifications
        $entityManager->flush();

        // Redirection selon le statut
        if (!$reservation->isStatutReservationEvent()) {
            return $this->redirectToRoute('app_reservation_evenement_invalides');
        } else {
            return $this->redirectToRoute('app_reservation_evenement_valides');
        }
    }

    // Passer les participants et événements à la vue
    $participants = $participantRepository->findAll();
    $evenements = $evenementRepository->findAll();

    return $this->render('enseignant/reservation_evenement/editreservation.html.twig', [
        'form' => $form->createView(),
        'reservation' => $reservation,
        'participants' => $participants,
        'evenements' => $evenements,
    ]);
}

    





    #[Route('/reservation-evenement/{id}/delete', name: 'reservation_evenement_delete')]
public function delete(
    ReservationEvenement $reservation,
    EntityManagerInterface $entityManager
): Response {
    // Suppression de la réservation
    $entityManager->remove($reservation);
    $entityManager->flush();

    // Redirection basée sur le statut de la réservation
    return $this->redirectToRoute(
        $reservation->isStatutReservationEvent() ? 'app_reservation_evenement_valides' : 'app_reservation_evenement_invalides'
    );
}


#[Route('/reservation/{id}', name: 'reservation_show', methods: ['GET'])]
public function show(
    int $id,
    ReservationEvenementRepository $reservationEvenementRepository
): Response {
    $reservation = $reservationEvenementRepository->find($id);

    if (!$reservation) {
        throw $this->createNotFoundException('Réservation non trouvée.');
    }

    $pdfDirectory = $this->getParameter('pdf_directory');
    $pdfFilePath = $pdfDirectory . '/reservation_' . $reservation->getId() . '.pdf';

    // Générer un hash unique basé sur l'état actuel de la réservation
    $currentHash = $this->generateReservationHash($reservation);

    // Chemin pour stocker le hash
    $hashFilePath = $pdfDirectory . '/reservation_' . $reservation->getId() . '.hash';

    // Vérifier si le hash a changé
    if (!file_exists($hashFilePath) || file_get_contents($hashFilePath) !== $currentHash) {
        // Supprimer l'ancien PDF s'il existe
        if (file_exists($pdfFilePath)) {
            unlink($pdfFilePath);
        }

        // Générer un nouveau PDF
        $pdfContent = $this->generatePdf($reservation);
        if (!file_put_contents($pdfFilePath, $pdfContent)) {
            throw new \RuntimeException('Impossible d\'écrire le fichier PDF.');
        }

        // Sauvegarder le nouveau hash
        file_put_contents($hashFilePath, $currentHash);
    }

    // Préparer les templates en fonction du statut
    if ($reservation->isStatutReservationEvent()) {
        return $this->render('enseignant/reservation_evenement/listreservationvalide.html.twig', [
            'reservation' => $reservation,
            'pdf_url' => $this->generateUrl('pdf_serve', ['filename' => basename($pdfFilePath)]),
        ]);
    } else {
        return $this->render('enseignant/reservation_evenement/listreservatevent.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
private function generateReservationHash(ReservationEvenement $reservation): string
{
    $data = [
        $reservation->getDateDebutRE()?->format('Y-m-d'),
        $reservation->getDateFinRE()?->format('Y-m-d'),
        $reservation->getClassReservationEvent(),
        $reservation->isStatutReservationEvent(),
    ];

    foreach ($reservation->getEvenements() as $evenement) {
        $data[] = $evenement->getId();
        $data[] = $evenement->getNomEvenement();
    }

    foreach ($reservation->getParticipants() as $participant) {
        $data[] = $participant->getId();
        $data[] = $participant->getNom();
    }

    // Convertir les données en JSON et calculer le hash
    return hash('sha256', json_encode($data));
}



    // Méthode pour générer le contenu du PDF
    private function generatePdf($reservation): string
    {
        $dompdf = new Dompdf();

        // Préparation des images en base64
        foreach ($reservation->getEvenements() as $evenement) {
            if ($evenement->getImageEvenement()) {
                $evenement->base64Image = $this->getImageBase64('uploads/photos/' . $evenement->getImageEvenement());
            } else {
                $evenement->base64Image = null;
            }
        }

        // Génération de l'HTML depuis un template
        $html = $this->renderView('enseignant/reservation_evenement/reservation_template.html.twig', [
            'reservation' => $reservation,
        ]);

        // Configuration et rendu du PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    // Route pour servir le fichier PDF
    #[Route('/pdf/{filename}', name: 'pdf_serve', methods: ['GET'])]
    public function servePdf(string $filename): Response
    {
        $pdfDirectory = $this->getParameter('pdf_directory');
        $filePath = $pdfDirectory . '/' . $filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Le fichier PDF n\'existe pas.');
        }

        return new BinaryFileResponse($filePath);
    }

    // Méthode pour convertir une image en base64
    public function getImageBase64(string $imagePath): ?string
    {
        $absolutePath = $this->getParameter('kernel.project_dir') . '/public/' . $imagePath;

        if (file_exists($absolutePath)) {
            $imageData = file_get_contents($absolutePath);
            $base64 = 'data:image/' . pathinfo($absolutePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imageData);
            return $base64;
        }

        return null;
    }
}




