<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
class UserupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('matricule', null, [
            'attr' => [
                'placeholder' => "Doctor Matricule",
            ]
        ])
        ->add('cas_med', null, [
            'attr' => ['placeholder' => 'Medical Case'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'you should tell us your Medical problem']),
            ],
        ])
        ->add('n_cnam', null, [
            'attr' => ['placeholder' => 'N° CNAM'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'you should give CNUM Number']),
                new Assert\Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'you should Put Number',
                ]),
            ],
        ])
        ->add('assurance', null, [
            'attr' => ['placeholder' => 'Assurance'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'you should Give Your Assurance Info']),
            ],
        ])
        ->add('num_assurance', null, [
            'attr' => ['placeholder' => 'N° Assurance'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'you should Write your assurance Nubmer']),
                new Assert\Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'you should Put Number',
                ]),
            ],
        ])
            ->add('email', null, [
                'attr' => ['placeholder' => 'Email@exemp.exp'],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Password'],
            ])
            ->add('name', null, [
                'attr' => ['placeholder' => 'Name'],
            ])
            ->add('lastname', null, [
                'attr' => ['placeholder' => 'Last Name'],
            ])
            ->add('date_birth', DateType::class, [
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'placeholder' => 'Gender',
            ])
            ->add('brochureFilename', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (PNG, JPEG, JPG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
