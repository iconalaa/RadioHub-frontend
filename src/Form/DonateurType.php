<?php

namespace App\Form;

use App\Entity\Donateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DonateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_Donateur')
            ->add('Prenom_Donateur')
            ->add('Type_Donateur')
            ->add('Email')
            ->add('Téléphone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donateur::class,
        ]);
    }
}
