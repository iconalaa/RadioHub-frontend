<?php

namespace App\Form;

use App\Entity\Interpretation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterpretationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('interpretation')

            ->add('urgency', ChoiceType::class, [
                'choices' => [
                    'Urgent' => 'urgent',
                    'Normal' => 'normal',
                    'Medium' => 'medium',
                ],
            ])
            ->add('description')
            ->add("save", SubmitType::class, [
                'label' => 'Save Interpretation',
                'attr' => ['class' => 'btn btn-primary']
            ])
              ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interpretation::class,
        ]);
    }
}
