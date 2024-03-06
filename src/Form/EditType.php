<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bodypart', null, [
                'constraints' => [
                ],
            ])
            ->add('patient', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'The patient cannot be blank.',
                    ]),
                ],
            ])
            ->add('filename', FileType::class, [
                'label' => 'filename',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid image.',
                    ]),
                ],
            ])
            ->add('aquisationDate',DateType::class,[
                'widget'=>'single_text',])
                ->add("save", SubmitType::class, [
                    'label' => 'Save Changes',
                    'attr' => ['class' => 'btn btn-primary']
                ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
