<?php
namespace App\Form;

use App\Entity\ReservationEvenement;
use App\Entity\Participant;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationEvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDebutRE', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('DateFinRE', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('ClassReservationEvent', TextType::class)
            
            // Utilisation de EntityType pour afficher les participants avec des cases à cocher
            // Ajout des participants avec détails dans choice_label
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationEvenement::class,
        ]);
    }
}
