<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface; // Import EntityManagerInterface
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ImageType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bodypart')
            ->add('patient', ChoiceType::class, [
                'choices' => $this->getPatients(),
                'choice_label' => function(User $user) {
                    // Assuming User entity has a method to get the full name
                    return $user->getUserIdentifier();
                },
                'choice_value' => null, // Use the whole User object as the choice value
                'placeholder' => 'Select a patient',
            ])
            ->add('aquisationDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('filename', FileType::class, [
                'label' => 'Filename',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => "This field cannot be blank."]),
                    new Assert\File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid DICOM file',
                        'mimeTypes' => ['application/dicom'], // Define the MIME type for DICOM files
                    ]),
                ],
            ])
            ->add("save", SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    private function getPatients(): array
{
    $userRepository = $this->entityManager->getRepository(User::class);
    $patients = $userRepository->findPatients();

    // Return the array of patients directly
    return $patients;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'patients' => null, // Define the 'patients' option and set its default value


        ]);
    }
}
