<?php

namespace App\Form;

use App\Entity\Report;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompteRenduType extends AbstractType
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
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
            ->add('date', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please provide the date']),
                ],
            ])
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
            ->add('doctor', ChoiceType::class, [ // Use ChoiceType instead of EntityType
                'choices' => $this->getDoctorChoices(), // Fetch choices dynamically
                'constraints' => [
                    new NotBlank(['message' => 'Please provide the doctor']),
                ],
            ])
            ->add('image', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please provide the image ID']),
                ],
            ]);
    }

    private function getDoctorChoices(): array
    {
        $doctors = $this->userRepository->findUsersByRole('ROLE_DOCTOR');
        $choices = [];
        foreach ($doctors as $doctor) {
            $choices[$doctor->getName()] = $doctor;
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
        ]);
    }
}
