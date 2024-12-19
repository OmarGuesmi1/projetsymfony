<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le nom de l'événement
            ->add('NomEvenement', TextType::class, [
                'label' => 'Nom de l\'événement',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom de l\'événement',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le nom de l\'événement ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'max' => 100,
                        'maxMessage' => 'Le nom de l\'événement ne peut pas dépasser 100 caractères.',
                    ]),
                ],
            ])

            // Champ pour la description de l'événement
            ->add('DescriptionEvenement', TextareaType::class, [
                'label' => 'Description de l\'événement',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6,
                    'placeholder' => 'Décrivez votre événement ici...',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La description de l\'événement est obligatoire.',
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'La description doit contenir au moins 10 caractères.',
                        'max' => 1000,
                        'maxMessage' => 'La description ne peut pas dépasser 1000 caractères.',
                    ]),
                ],
            ])

            // Champ pour l'image de l'événement
            ->add('ImageEvenement', FileType::class, [
                'label' => 'Image de l\'événement',
                'mapped' => false, // Ce champ n'est pas lié directement à l'entité
                'required' => false,
                'attr' => [
                    'class' => 'form-control-file',
                ],
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Seules les images JPEG, PNG et GIF sont autorisées.',
                        'maxSizeMessage' => 'La taille maximale du fichier est de 2 Mo.',
                    ]),
                ],
            ])

            // Champ pour le type d'événement
            ->add('TypeEvenement', ChoiceType::class, [
                'label' => 'Type d\'événement',
                'choices' => [
                    'Professionnel' => [
                        'Réunion d\'affaires' => 'reunion_affaires',
                        'Networking' => 'networking',
                        'Lancement de produit' => 'lancement_produit',
                        'Forum' => 'forum',
                        'Hackathon' => 'hackathon',
                    ],
                    'Culturel et artistique' => [
                        'Projection de film' => 'projection_film',
                        'Lecture publique' => 'lecture_publique',
                        'Performance artistique' => 'performance_artistique',
                        'Débat culturel' => 'debat_culturel',
                    ],
                    'Éducatif et formateur' => [
                        'Conférence académique' => 'conference_academique',
                        'Formation pratique' => 'formation_pratique',
                        'Bootcamp' => 'bootcamp',
                        'Journée porte ouverte' => 'journee_portes_ouvertes',
                        'Salon étudiant' => 'salon_etudiant',
                    ],
                    'Loisir et détente' => [
                        'Fête à thème' => 'fete_theme',
                        'Soirée dansante' => 'soiree_dansante',
                        'Brunch ou pique-nique' => 'brunch',
                        'Sortie en groupe' => 'sortie_groupe',
                    ],
                    'Communautaire et solidaire' => [
                        'Collecte de fonds' => 'collecte_fonds',
                        'Campagne de sensibilisation' => 'campagne_sensibilisation',
                        'Journée bénévole' => 'journee_benevole',
                        'Festival local' => 'festival_local',
                    ],
                    'Sportif' => [
                        'Tournoi amateur' => 'tournoi_amateur',
                        'Match d\'exhibition' => 'match_exhibition',
                        'Journée sportive' => 'journee_sportive',
                        'Randonnée' => 'randonnee',
                    ],
                    'En ligne et hybride' => [
                        'Masterclass' => 'masterclass',
                        'Podcast en direct' => 'podcast_direct',
                        'Panel de discussion en ligne' => 'panel_discussion',
                    ],
                    'Autres' => [
                        'Expérience immersive (VR/AR)' => 'experience_vr',
                        'Autre' => 'autre',
                    ],
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'placeholder' => 'Sélectionnez un type',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez sélectionner un type d\'événement.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
