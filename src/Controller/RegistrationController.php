<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Radiologist;
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

    public function registerDoctor(Request $request, UserRepository $findUser, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $doctor = new Doctor();

        $form = $this->createFormBuilder()
            ->add('user', UserType::class, [
                'data' => $user,
            ])
            ->add('doctor', DoctorType::class, [
                'data' => $doctor,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ! user ----
            $userData = $form->get('user')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($userData, $form->get('user')->get('password')->getData());
            $userData->setPassword($hashedPassword);
            $userData->setRoles(['ROLE_DOCTOR']);
            $entityManager->persist($userData);
            $entityManager->flush();
            // ! Doctor ----
            $doctorData = $form->get('doctor')->getData();
            $doctorData->setUser($userData);
            $entityManager->persist($doctorData);
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
        $patient = new Patient();

        $form = $this->createFormBuilder()
            ->add('user', UserType::class, [
                'data' => $user,
            ])
            ->add('patient', PatientType::class, [
                'data' => $patient,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ! user ----
            $userData = $form->get('user')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($userData, $form->get('user')->get('password')->getData());
            $userData->setPassword($hashedPassword);
            $userData->setRoles(['ROLE_PATIENT']);
            $entityManager->persist($userData);
            $entityManager->flush();
            // ! Patient ----
            $patientData = $form->get('patient')->getData();
            $patientData->setUser($userData);
            $entityManager->persist($patientData);
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
        $radiologist = new Radiologist();

        $form = $this->createFormBuilder()
            ->add('user', UserType::class, [
                'data' => $user,
            ])
            ->add('radiologist', RadiologistType::class, [
                'data' => $radiologist,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ! user ----
            $userData = $form->get('user')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($userData, $form->get('user')->get('password')->getData());
            $userData->setPassword($hashedPassword);
            $userData->setRoles(['ROLE_RADIOLOGIST']);
            $entityManager->persist($userData);
            $entityManager->flush();
            // ! Patient ----
            $radioData = $form->get('radiologist')->getData();
            $radioData->setUser($userData);
            $entityManager->persist($radioData);
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
