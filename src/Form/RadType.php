<?php

namespace App\Form;

use App\Repository\ImageRepository;
use App\Entity\CompteRendu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class RadType extends AbstractType
{
    private $imagesRepository;

    public function __construct(ImageRepository $imagesRepository)
    {
        $this->imagesRepository = $imagesRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('interpretation_rad', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please provide your interpretation']),
                ],
                'invalid_message' => 'Custom error message for interpretation_med field',
                'attr' => [
                    'class' => 'form-control', // Add form-control class for Bootstrap styling
                    'rows' => 5, // Set the number of visible rows for the textarea
                ],
            ])
            
            ->add('id_doctor', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Assign a doctor']),
                ],
                'invalid_message' => 'Custom error message for interpretation_rad field',
            ])
          ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteRendu::class,
        ]);
    }
}
