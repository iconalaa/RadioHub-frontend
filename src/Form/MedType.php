<?php

namespace App\Form;

use App\Entity\CompteRendu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;

class MedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('interpretationMed', TextareaType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Please provide your interpretation']),
            ],
            'invalid_message' => 'Custom error message for interpretation_med field',
            'attr' => [
                'class' => 'form-control', // Add form-control class for Bootstrap styling
                'rows' => 5, // Set the number of visible rows for the textarea
            ],
        ])
            ->add('date', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please add the date']),
                    new DateTime(['format' => 'Y-m-d', 'message' => 'Invalid date format. Please use YYYY-MM-DD.']),
                ],
                'invalid_message' => 'Custom error message for date field',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompteRendu::class,
        ]);
    }
}
