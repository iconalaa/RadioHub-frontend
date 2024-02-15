<?php

namespace App\Form;

use App\Entity\CompteRendu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class MedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('interpretationMed', TextType::class, [
                'label' => 'Interprétation du médecin',
                // 'required' => false, // pour permettre que le champ soit vide
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                // 'required' => false, // pour permettre que le champ soit vide
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompteRendu::class,
        ]);
    }
}