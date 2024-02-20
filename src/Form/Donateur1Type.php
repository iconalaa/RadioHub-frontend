<?php

namespace App\Form;

use App\Entity\Donateur;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Donateur1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_Donateur', null, [
                'attr' => [
                    'placeholder' => 'Nom Donateur'
                ]
            ])
            ->add('Prenom_Donateur', null, [
                'attr' => [
                    'placeholder' => 'Prenom Donateur'
                ]
            ])
            ->add('Type_Donateur', null, [
                'attr' => [
                    'placeholder' => 'Type Donateur'
                ]
            ])
            ->add('Email', null, [
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('Telephone', null, [
                'attr' => [
                    'placeholder' => 'Telephone'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donateur::class,
        ]);
    }
}
