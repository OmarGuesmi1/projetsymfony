<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant')]
class ParticipantController extends AbstractController
{
    #[Route('/', name: 'participant_index')]
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('enseignant/participant/showparticipant.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'participant_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $participant = new Participant();
    $form = $this->createForm(ParticipantType::class, $participant);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($participant);
        $entityManager->flush();

        // Redirection vers la route 'participant_index' après l'ajout du participant
        return $this->redirectToRoute('participant_index');
    }

    return $this->render('enseignant/participant/addparticipant.html.twig', [
        'participant' => $participant,
        'form' => $form->createView(),
    ]);
}


    #[Route('/show/{id}', name: 'participant_show')]
    public function show(Participant $participants): Response
    {
        return $this->render('enseignant/participant/showparticipantbyid.html.twig', [
            'participants' => $participants,
        ]);
    }

    #[Route('/{id}/edit', name: 'participant_edit')]
    public function edit(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('participant_index');
        }

        return $this->render('enseignant/participant/editparticipant.html.twig', [
            'participant' => $participant,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'participant_delete')]
    public function delete(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $participant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('participant_index');
    }
}
