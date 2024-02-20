<?php

namespace App\Form;

use App\Entity\Gratification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class Gratification1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date_grat',)
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
                'placeholder' => 'Choose an option', 
            ])
            ->add('ID_Donateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gratification::class,
        ]);
    }
}
