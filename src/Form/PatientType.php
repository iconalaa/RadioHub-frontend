<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cas_med',null,[
                'attr'=>[
                    'placeholder' => "Medical Case"
                ]
            ])
            ->add('n_cnam',null,[
                'attr'=>[
                    'placeholder' => "N° CNAM"
                ]
            ])
            ->add('assurance',null,[
                'attr'=>[
                    'placeholder' => "Assurance"
                ]
            ])
            ->add('num_assurance',null,[
                'attr'=>[
                    'placeholder' => "N° Assurance"
                ]
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
