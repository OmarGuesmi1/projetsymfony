<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class EnseignantController extends AbstractController
{
    #[Route('/enseignant', name: 'app_enseignant')]
    public function index(): Response
    {
        return $this->render('enseignant/enseignatevent.html.twig', [
            'controller_name' => 'EnseignantController',
        ]);
    }
    #[Route('/createevent', name: 'app_createevent')]
     
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new Evenement entity
        $evenement = new Evenement();

        // Create the form using EventType form class
        $form = $this->createForm(EventType::class, $evenement);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the uploaded file (if any)
            $imageFile = $form->get('ImageEvenement')->getData();
            if ($imageFile) {
                // Generate a unique filename and save the file in the configured directory
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('photo_upload_directory'), // Ensure this parameter is defined in services.yaml
                        $newFilename
                    );

                    // Update the Evenement entity with the new filename
                    $evenement->setImageEvenement($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'File upload failed: ' . $e->getMessage());
                    return $this->redirectToRoute('create_event');
                }
            }

            // Persist the Evenement entity to the database
            $entityManager->persist($evenement);
            $entityManager->flush();

            // Add a success flash message
            $this->addFlash('success', 'Event created successfully!');

            // Redirect to another page (e.g., the event list page)
            return $this->redirectToRoute('event_list');
        }

        // Render the form template
        return $this->render('enseignant/enseignantevent/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   
    #[Route('/event_list', name: 'event_list')]

    public function list(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les paramètres de recherche et de vue
        $search = $request->query->get('search', '');
        $view = $request->query->get('view', 'cards');

        // Construire la requête pour récupérer les événements
        $eventRepository = $entityManager->getRepository(Evenement::class);
        $queryBuilder = $eventRepository->createQueryBuilder('e');

        if (!empty($search)) {
            $queryBuilder->where('e.NomEvenement LIKE :search')
                         ->setParameter('search', '%' . $search . '%');
        }

        $evenements = $queryBuilder->getQuery()->getResult();

        // Retourner la vue
        return $this->render('enseignant/enseignantevent/llistevenement.html.twig', [
            'evenements' => $evenements,
            'view' => $view,
        ]);
    }

    
    
    #[Route('/event/edit/{id}', name: 'app_edit_event')]

    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'événement à modifier
        $evenement = $entityManager->getRepository(Evenement::class)->find($id);

        if (!$evenement) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        // Créer le formulaire en y passant l'objet de l'événement
        $form = $this->createForm(EventType::class, $evenement);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image si un nouveau fichier est téléchargé
            $imageFile = $form->get('ImageEvenement')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('uploads_directory'), // Chemin configuré dans services.yaml
                    $newFilename
                );
                $evenement->setImageEvenement($newFilename);
            }

            // Sauvegarder les modifications
            $entityManager->persist($evenement);
            $entityManager->flush();

            // Ajouter un message flash et rediriger
            $this->addFlash('success', 'L\'événement a été mis à jour avec succès.');
            return $this->redirectToRoute('event_list');
        }

        // Affichage de la vue du formulaire d'édition
        return $this->render('enseignant/enseignantevent/editevent.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
        ]);
    }

    #[Route('/event/delete/{id}', name: 'app_delete_event')]

    public function delete(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'événement à supprimer
        $evenement = $entityManager->getRepository(Evenement::class)->find($id);

        if (!$evenement) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            // Supprimer l'événement de la base de données
            $entityManager->remove($evenement);
            $entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', 'L\'événement a été supprimé avec succès.');
        } else {
            // Message flash en cas d'échec de la validation CSRF
            $this->addFlash('danger', 'Échec de la suppression de l\'événement. Veuillez réessayer.');
        }

        // Redirection vers la liste des événements
        return $this->redirectToRoute('event_list');
    }

    #[Route('/evenement/{id}', name: 'app_show_event', methods: ['GET'])]
    public function showEvenement(Evenement $evenement): Response
    {
        // Message à inclure dans le QR code
        $message = sprintf(
            "🎉 Événement Unique à Ne Pas Manquer ! 🎉\n\n" .
            "📌 **ID de l'Événement : %s\n" .
            "📍 **Nom : %s\n" .
            "🔖 **Type : %s\n" .
            "📝 **Description : %s\n\n" .
            "👉 **Vous souhaitez participer ? Envoyez-nous vos informations (Nom, Prénom, ID de l'Événement et Numéro de Téléphone) dès maintenant à :\n" .
            "📧 **biblionet.evenement.service@gmail.com**\n\n" .
            "📅 Réservez votre place et rejoignez-nous pour une expérience inoubliable !",
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

        // Renvoyer la réponse avec la vue Twig
        return $this->render('enseignant/enseignantevent/qrcode.html.twig', [
            'evenement' => $evenement,
            'qrCodeData' => $qrCodeData,
        ]);
    }
}







