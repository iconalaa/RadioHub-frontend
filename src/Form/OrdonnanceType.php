<?php

namespace App\Form;

use App\Entity\Ordonnance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
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
                ],
                'invalid_message' => 'Custom error message for date field',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}
