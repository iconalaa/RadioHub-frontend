<?php

namespace App\Form;

use App\Entity\Prescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please provide your prescription']),
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
            ])
            ->add('signatureFilename', FileType::class, [
                'label' => 'Signature (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M', // Adjust max size as needed
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            // Add more image MIME types if needed
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG).',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
        ]);
    }
}
