<?php

namespace App\Form;

use App\Entity\Droit;
use App\Entity\Radiologist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DroitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('role')
            ->add('radioloqist', EntityType::class, [
                'class' => Radiologist::class,
                'choices' => $options['radiologists'],
                'choice_label' => 'id', // Customize the choice label as needed
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Droit::class,
            'radiologists' => [], // Define default value for the radiologists option

        ]);
        $resolver->setAllowedTypes('radiologists', 'array');

    }

}
