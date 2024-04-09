<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numSalle',TextType::class,
            [
                'label' => false,
                'attr' => ['placeholder' => ' room number'],
            ])
            ->add('numDep',TextType::class,
            [
                'label' => false,
                'attr' => ['placeholder' => ' department number'],
                
            ])
            ->add('etatSalle',ChoiceType::class, [
                'choices' => [
                    'Select state...' => '',
                    'reservé' => 'reservé',
                    'Non_reservé' => 'Non_reservé',
                    
                ],
                'label' => false,
            ])
            ->add('TypeSalle', ChoiceType::class, [
                'choices' => [
                    'Select type room...' => '',
                    'Scanner' => 'Scanner',
                    'Echographie' => 'Echographie',
                    'IRM' => 'IRM',
                    'EOS' => 'EOS',
                    'Doppler' => 'Doppler',
                    'Mammographie' => 'Mammographie',
                ],
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
