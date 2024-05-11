<?php

namespace App\Form;

use App\Repository\ImageRepository;
use App\Entity\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface; 


class RadType extends AbstractType
{
    private $entityManager;
    private $imagesRepository;

    public function __construct(EntityManagerInterface $entityManager, ImageRepository $imagesRepository)
    {
        $this->entityManager = $entityManager;
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
            
            ->add('doctor', ChoiceType::class, [
                'choices' => $this->getDoctors(),
                'choice_label' => function(User $user) {
                    // Assuming User entity has a method to get the full name
                    return $user->getUserIdentifier();
                },
                'choice_value' => null, // Use the whole User object as the choice value
                'placeholder' => 'Select a Doctor',
            ])
          ;
    }


    private function getDoctors(): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $patients = $userRepository->findDoctors();
    
        // Return the array of patients directly
        return $patients;
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
            'doctors' => null, // Define the 'patients' option and set its default value



        ]);
    }
}