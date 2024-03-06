<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Repository\ReclamationRepository;
use App\Repository\RendezVousRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('desc_rep',TextType::class,
            [
                'label' => false,
                'attr' => [
                    'placeholder' => 'your response ',
                ]
            ])
            ->add('date_rep', DateType::class,
            [
                'label' => false,
                'format' => 'yyyy-MM-dd', // Adjust the format as needed
                
            ])

            ->add('reponse', EntityType::class, [
                'class' => RendezVousType::class,
                'choice_label' => 'id', // Replace with the actual property you want to display
                'query_builder' => function (ReclamationRepository $er) {
                    return $er->createQueryBuilder('R')
                        ->orderBy('R.id', 'ASC'); // Replace with the actual property you want to order by
                },
                'placeholder' => 'Select a claim ...',
                'label' => false,
                'attr' => ['placeholder' => 'claim'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
