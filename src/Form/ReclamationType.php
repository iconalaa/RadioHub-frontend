<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_user',TextType::class,
            [
                'label' => false,
                'attr' => ['placeholder' => 'votre ID'],

                
            ])
            ->add('etat_rec', ChoiceType::class, [
                'choices' => [
                    'Traitée' => 'Traitée',
                    'NonTraitée' => 'NonTraitée',
                  
                ],
                'label' => false,
            ])
            ->add('date_rec', DateType::class,
            [
                'label' => false,
                // 'widget' => 'single_text', // This ensures a single text input without a date picker
                
               'widget' => 'single_text', // This ensures a single text input without a date picker
               'format' => 'yyyy-MM-dd', // Adjust the format as needed
            ])
            ->add('desc_rec',TextType::class,
            [
                'label' => false,
                'attr' => ['placeholder' => ' Add a description'],

                
            ])
            ->add('type_rec', ChoiceType::class, [
                'choices' => [
                    'Select type reclamation...' => '', 
                    'Service_Client' => 'Service_Client',
                    'Systeme' => 'Systeme',
                    'Equipements' => 'Equipements',
                  
                ],
                'label' => false,
            ])
            ->add('reponse')
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
