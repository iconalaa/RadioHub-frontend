<?php

namespace App\Form;

use App\Repository\ImagesRepository;
use App\Entity\CompteRendu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
            ->add('interpretation_rad')
            ->add('id_medecin')
            ->add('id_image', EntityType::class, [
                'class' => 'App\Entity\Images',
                'choices' => $this->imagesRepository->findImagesWithoutCompteRendu(),
                'choice_label' => function($image) {
                    return $image->getpatient();
                },
                'attr' => ['class' => 'form-control'],
                'placeholder' => '', // Set the placeholder to an empty string
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteRendu::class,
        ]);
    }
}
