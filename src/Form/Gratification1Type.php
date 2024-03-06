<?php

namespace App\Form;

use App\Entity\Gratification;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class Gratification1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        // Inside your form type class
        ->add('Date_grat', DateType::class, [
            'widget' => 'single_text',
            
            'html5' => true,
            'attr' => [
                'min' => 'currentDate',
            ],
            'input' => 'datetime',
            'required' => true,
        ])
        
            ->add('Titre_Grat', null, [
                'attr' => [
                    'placeholder' => 'Titre de la gratification'
                ]
            ])
            ->add('Desc_Grat', null, [
                'attr' => [
                    'placeholder' => 'Description'
                ]
            ])
            ->add('Type_Grat', ChoiceType::class, [
                'choices' => [
                    'Monnaitaire' => 'Monnaitaire',
                    'Matérielle' => 'Matérielle',
                ],
                
            ])

            ->add('Montant') 
            ->add('Type_Machine')
            ->add('ID_Donateur');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gratification::class,
        ]);
    }
}
