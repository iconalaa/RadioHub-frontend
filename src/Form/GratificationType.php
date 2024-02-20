<?php

namespace App\Form;

use App\Entity\Gratification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GratificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date_grat')
            ->add('Titre_Grat')
            ->add('Desc_Grat')
            ->add('Type_Grat')
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
