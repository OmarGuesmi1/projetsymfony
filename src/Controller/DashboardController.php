<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Notificationevent;
use App\Entity\ReservationEvenement;
use App\Repository\NotificationeventRepository;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $notifications = $entityManager->getRepository(Notificationevent::class)->findAll();
    
        // Filtrer les notifications pour ne garder que celles liées à une réservation avec StatutReservationEvent null
        $filteredNotifications = array_filter($notifications, function ($notification) {
            $reservation = $notification->getReservationEvenement();
            return $reservation && $reservation->isStatutReservationEvent() == null;
        });
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'notifications' => $filteredNotifications,

        ]);
    }

    #[Route('/showLivreAdmin', name: 'app_showLivreAdmin')]
    public function showLivreAdmin(EntityManagerInterface $entityManager): Response
    {
        $notifications = $entityManager->getRepository(Notificationevent::class)->findAll();
    
        // Filtrer les notifications pour ne garder que celles liées à une réservation avec StatutReservationEvent null
        $filteredNotifications = array_filter($notifications, function ($notification) {
            $reservation = $notification->getReservationEvenement();
            return $reservation && $reservation->isStatutReservationEvent() == null;
        });
        return $this->render('dashboardd/showAdminLivres.html.twig', [
            'notifications' => $filteredNotifications,
        ]);
    }

    #[Route('/ajouterLivre', name: 'app_ajouterLivre')]
    public function ajouterLivre(EntityManagerInterface $entityManager): Response
    {
        $notifications = $entityManager->getRepository(Notificationevent::class)->findAll();
    
        // Filtrer les notifications pour ne garder que celles liées à une réservation avec StatutReservationEvent null
        $filteredNotifications = array_filter($notifications, function ($notification) {
            $reservation = $notification->getReservationEvenement();
            return $reservation && $reservation->isStatutReservationEvent() == null;
        });
        return $this->render('dashboardd/ajouterLivre.html.twig', [
            'notifications' => $filteredNotifications,
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(EntityManagerInterface $entityManager): Response
    {
        $notifications = $entityManager->getRepository(Notificationevent::class)->findAll();
    
        // Filtrer les notifications pour ne garder que celles liées à une réservation avec StatutReservationEvent null
        $filteredNotifications = array_filter($notifications, function ($notification) {
            $reservation = $notification->getReservationEvenement();
            return $reservation && $reservation->isStatutReservationEvent() == null;
        });
        return $this->render('dashboardd/profile.html.twig', [
            'notifications' => $filteredNotifications,
        ]);
    }
    #[Route('/notifications', name: 'notification_list')]
    public function notificationList(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les notifications
        $notifications = $entityManager->getRepository(Notificationevent::class)->findAll();
    
        // Filtrer les notifications pour ne garder que celles liées à une réservation avec StatutReservationEvent null
        $filteredNotifications = array_filter($notifications, function ($notification) {
            $reservation = $notification->getReservationEvenement();
            return $reservation && $reservation->isStatutReservationEvent() == null;
        });
    
        return $this->render('dashboard/notification2.html.twig', [
            'notifications' => $filteredNotifications,
        ]);
    }
    


#[Route('/validate-notification/{id}', name: 'validate_notification')]
public function validateNotification(
    int $id,
    EntityManagerInterface $entityManager,
    NotificationeventRepository $notificationRepository
): Response {
    // Récupérer la réservation
    $reservation = $entityManager->getRepository(ReservationEvenement::class)->find($id);

    if (!$reservation) {
        throw $this->createNotFoundException('Réservation non trouvée.');
    }

    // Modifier le statut de la réservation
    $reservation->setStatutReservationEvent(true);

    // Supprimer la notification associée
    
    // Sauvegarder les modifications
    $entityManager->flush();

    // Récupérer le nouveau nombre de notifications

    // Rediriger vers la liste des notifications avec le compteur
    return $this->redirectToRoute('notification_list');
}

}
