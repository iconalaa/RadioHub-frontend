<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Repository\ReclamationRepository;
use App\Repository\RendezVousRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('desc_rep', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Your response',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter a description']),
                ],
            ])
            ->add('date_rep', DateType::class, [
                'label' => 'Date',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter a date']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
