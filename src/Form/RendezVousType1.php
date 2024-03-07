<?php

namespace App\Form;

use App\Entity\RendezVous;
use App\Entity\Salle;
use App\Repository\SalleRepository;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;
//use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
//use captcha\captchaBundle\Validator\constraints\validateCaptcha;
class RendezVousType1 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('nomPatient', null, [
            'label' => 'NAME :',
            'attr' => ['placeholder' => ' name'],
            'constraints' => [
              
                new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'Your name should be at least {{ limit }} characters.',
                    'maxMessage' => 'Your name should be no more than {{ limit }} characters.',
                ]),
            ],
        ])
        ->add('prenomPatient', null, [
            
            'label' => 'LASTNAME :',
            'attr' => ['placeholder' => ' lastname'],
            'constraints' => [
                
                new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'Your lastname should be at least {{ limit }} characters.',
                    'maxMessage' => 'Your lastname should be no more than {{ limit }} characters.',
                ]),
                
            ],
        ])


        ->add('mailPatient', null, [
            

               'attr' => ['placeholder' => 'Email@exemp.exp'],
            ])


        /*->add('statusRV',ChoiceType::class,
        [
            'label' => false,
            'empty_data' => '', // Ensure empty data is submitted if the field is left blank
            'choices' => [
                
                'confirmed' => 'confirmed',
                'unconfirmed' => 'unconfirmed',]
            
        ])
        */


            ->add('dateRV', DateType::class,
            [
                'label' => false,
                'widget' => 'single_text', // This ensures a single text input without a date picker
               'format' => 'yyyy-MM-dd', // Adjust the format as needed
                 'constraints' => [
               new NotBlank([
                   'message' => 'Please enter the date.',
               ]),
                
            ]
            ])


            ->add('typeExam', ChoiceType::class, [
                'choices' => [
                    'Select type exam...' => '', // Empty choice as a placeholder
                    'Scanner' => 'Scanner',
                    'Echographie' => 'Echographie',
                    'IRM' => 'IRM',
                    'EOS' => 'EOS',
                    'Doppler' => 'Doppler',
                    'Mammographie' => 'Mammographie',
                ],
                'label' => false,
                
                
            ])

           
               
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }





}
