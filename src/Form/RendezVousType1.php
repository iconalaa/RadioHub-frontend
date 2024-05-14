<?php

namespace App\Form;

use App\Entity\RendezVous;
use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityManagerInterface;


class RendezVousType1 extends AbstractType

{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('dateRV', DateType::class,
            [
                'label' => false,
                'widget' => 'single_text',
               'format' => 'yyyy-MM-dd', 
                 'constraints' => [
               new NotBlank([
                   'message' => 'Please enter the date.',
               ]),
                
            ]
            ])


            ->add('typeExam', ChoiceType::class, [
                'choices' => [
                    'Select type exam...' => '', 
                    'Scanner' => 'Scanner',
                    'Echographie' => 'Echographie',
                    'IRM' => 'IRM',
                    'EOS' => 'EOS',
                    'Doppler' => 'Doppler',
                    'Mammographie' => 'Mammographie',
                ],
                'label' => false,
                
                
            ])
            ->add('salle', ChoiceType::class, [
                'choices' => $this->getSalles(),
                'choice_label' => function(Salle $salle) {
                    // Assuming User entity has a method to get the full name
                    return $salle->getNumSalle();
                },
                'choice_value' => null, // Use the whole User object as the choice value
                'placeholder' => 'Select a salle',
            ])
            
            
            
        


               
        ;
    }
    private function getSalles(): array
{
    $sallerRepository = $this->entityManager->getRepository(Salle::class);
    $salles = $sallerRepository->findAll();


    return $salles;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }





}
