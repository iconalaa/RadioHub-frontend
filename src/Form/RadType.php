<?php

namespace App\Form;

use App\Repository\ImagesRepository;
use App\Entity\CompteRendu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

class RadType extends AbstractType
{
    private $imagesRepository;

    public function __construct(ImagesRepository $imagesRepository)
    {
        $this->imagesRepository = $imagesRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('interpretation_rad', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please provide your interpretation']),
                ],
                'invalid_message' => 'Custom error message for interpretation_rad field',
            ])
            ->add('id_doctor', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Assign a doctor']),
                ],
                'invalid_message' => 'Custom error message for interpretation_rad field',
            ])
            ->add('id_image', EntityType::class, [
                'class' => 'App\Entity\Images',
                'choices' => $this->imagesRepository->findImagesWithoutCompteRendu(),
                'choice_label' => function($image) {
                    return $image->getpatient();
                },
                'attr' => ['class' => 'form-control'],
                'placeholder' => '', // Set the placeholder to an empty string
                'constraints' => [
                    new NotBlank(['message' => 'Assign an image to the patient']),
                ],
                'invalid_message' => 'Custom error message for interpretation_rad field',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteRendu::class,
        ]);
    }
}
