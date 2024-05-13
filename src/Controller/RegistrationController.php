<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\DoctorType;
use App\Form\PatientType;
use App\Form\RadiologistType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

#[Route('/register')]
class RegistrationController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(): Response
    {
        return $this->render('registration/register.html.twig');
    }

    #[Route('/doctor', name: 'app_register_doctor', methods: ['GET', 'POST'])]

    public function registerDoctor(MailerInterface $mailer,Request $request, UserRepository $findUser, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createFormBuilder()
           
            ->add('doctor', DoctorType::class, [
                'data' => $user,
            ])
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
          
            // ! user ----
            $userData = $form->get('doctor')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($userData, $form->get('doctor')->get('password')->getData());
            $userData->setPassword($hashedPassword);
            $userData->setRoles(['ROLE_WAITING_DOCTOR']);
            $userData->setBrochureFilename("x");
            $entityManager->persist($userData);
            $entityManager->flush();
        
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/doctor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/patient', name: 'app_register_patient', methods: ['GET', 'POST'])]

    public function registerPatient(Request $request, UserRepository $findUser, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createFormBuilder()
            ->add('user', PatientType::class, [
                'data' => $user,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ! user ----
            $userData = $form->get('user')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($userData, $form->get('user')->get('password')->getData());
            $userData->setPassword($hashedPassword);
            $userData->setBrochureFilename("x");
            $userData->setRoles(['ROLE_PATIENT']);
            $entityManager->persist($userData);
            $entityManager->flush();
         

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/patient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/radiologist', name: 'app_register_radiologist', methods: ['GET', 'POST'])]

    public function registerRadiologist(Request $request, UserRepository $findUser, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createFormBuilder()
            ->add('user', RadiologistType::class, [
                'data' => $user,
            ])
           
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ! user ----
            $userData = $form->get('user')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($userData, $form->get('user')->get('password')->getData());
            $userData->setPassword($hashedPassword);
            $userData->setBrochureFilename("x");
            $userData->setRoles(['ROLE_WAITING_RADIOLOGIST']);
            $entityManager->persist($userData);
            $entityManager->flush();
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/radiologist.html.twig', [
            'form' => $form->createView(),
        ]);
    }







    // ! ------------------------------
}
