<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            
            "attr" => [
                "class" => "form-control"
            ]
        ])
            ->add('content', TextareaType::class, [
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add('createdAt')
            ->add('image', FileType::class, [
                'data_class' => null,           // added after to debug...
                "label" => "Photo de l'article",
                "mapped" => true,
                "required" => false, 
                "constraints" => [
                    new File([
                        "maxSize" => "3M",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/jpg",
                            "image/png",
                            "image/JPEG",
                            "image/JPG",
                            "image/PNG"
                        ],
                        "mimeTypesMessage" => "Formats acceptés : jpeg, jpg ou png"
                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
