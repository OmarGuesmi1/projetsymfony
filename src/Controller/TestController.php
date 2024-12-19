<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;





class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }


    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig', );
    }


    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig', );
    }


    #[Route('/singn_in', name: 'singn_in')]
    public function singn_in(): Response
    {
        return $this->render('singnin.html.twig', );
    }

    #[Route('/singn_up', name: 'singn_up')]
    public function singn_up(): Response
    {
        return $this->render('singnup.html.twig', );
    }


    #[Route('/livres', name: 'livres_show')]
    public function livres_show(): Response
    {
        $books = array(
            array('id' => 1, 'image' => '/images/Victor-Hugo.jpg', 'title' => 'Victor Hugo', 'description' => 'victor.hugo@gmail.com '),
            array('id' => 2, 'image' => '/images/william.jpg', 'title' => ' William Shakespeare', 'description' =>  ' william.shakespeare@gmail.com'),
            array('id' => 3, 'image' => '/images/Taha-Hussein.jpg', 'title' => 'Taha Hussein', 'description' => 'taha.hussein@gmail.com'),
            );
        return $this->render('livres.html.twig', 
    ['books' => $books]);
    }

    #[Route('/showevenement', name: 'showevent')]
public function show(EvenementRepository $evenementRepository, Request $request): Response
{
    // Récupérer les paramètres de recherche et de type depuis la requête
    $search = $request->query->get('search', '');
    $type = $request->query->get('type', '');
    
    // Construire la requête en fonction des paramètres fournis
    $queryBuilder = $evenementRepository->createQueryBuilder('e');

    if ($search) {
        $queryBuilder->andWhere('e.NomEvenement LIKE :search OR e.DescriptionEvenement LIKE :search')
            ->setParameter('search', '%' . $search . '%');
    }

    if ($type) {
        $queryBuilder->andWhere('e.TypeEvenement = :type')
            ->setParameter('type', $type);
    }

    // Exécuter la requête pour obtenir les événements filtrés
    $evenements = $queryBuilder->getQuery()->getResult();

    // Récupérer tous les types uniques d'événements pour alimenter le filtre
    $types = $evenementRepository->createQueryBuilder('e')
        ->select('DISTINCT e.TypeEvenement')
        ->getQuery()
        ->getResult();

    // Envoyer les données au template
    return $this->render('enseignant/enseignantevent/pageeventshow.html.twig', [
        'evenements' => $evenements,
        'types' => array_column($types, 'TypeEvenement'),
    ]);
}



#[Route('/evenements/{id}', name: 'showeventid', methods: ['GET'])]
public function showEvenement(Evenement $evenement): Response
{
    // Récupérer la réservation associée à l'événement
    $reservation = $evenement->getReservationEvenement();

    // Message à inclure dans le QR code
    $message = sprintf(
        "🎉 Événement Unique à Ne Pas Manquer ! 🎉\n\n" .
        "📌 **ID de l'Événement : %s\n" .
        "📍 **Nom : %s\n" .
        "🔖 **Type : %s\n" .
        "📝 **Description : %s\n\n" .
        "👉 **Vous souhaitez participer ? Envoyez-nous vos informations (Nom, Prénom, ID de l'Événement et Numéro de Téléphone) dès maintenant à :\n" .
        "📧 **biblionet.evenement.service@gmail.com**\n\n" .
        "📅 Réservez votre place et rejoignez-nous pour une expérience inoubliable !\n\n" .
        "⏰ **Attention : Veuillez envoyer votre mail au moins 7 jours avant l'événement pour garantir votre place !**",
        $evenement->getId(),
        $evenement->getNomEvenement(),
        $evenement->getTypeEvenement(),
        $evenement->getDescriptionEvenement()
    );
    
    // Construire le QR Code
    $qrCode = Builder::create()
        ->writer(new PngWriter())
        ->data($message) // Le message sera directement intégré dans le QR code
        ->encoding(new Encoding('UTF-8'))
        ->size(300) // Taille en pixels
        ->margin(10) // Marge autour du QR code
        ->build();

    // Convertir le QR code en Base64 pour l'affichage dans la vue
    $qrCodeData = 'data:image/png;base64,' . base64_encode($qrCode->getString());

    // Renvoyer la réponse avec la vue Twig et les données de l'événement, y compris la réservation
    return $this->render('enseignant/enseignantevent/pageshowid.html.twig', [
        'evenement' => $evenement,
        'reservation' => $reservation,  // Ajouter la réservation
        'qrCodeData' => $qrCodeData,
    ]);
}


}
