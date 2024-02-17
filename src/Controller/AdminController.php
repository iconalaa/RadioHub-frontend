<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DoctorRepository;
use App\Repository\PatientRepository;
use App\Repository\RadiologistRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $addUser = new User();
        $form = $this->createForm(UserType::class, $addUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $addUser->setPassword(
                $userPasswordHasher->hashPassword(
                    $addUser,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($addUser);
            $entityManager->flush();
        }
        $users = $user->findAll();
        return $this->render('admin/user.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
        ]);
    }



    #[Route('/update/{id}', name: 'app_update_user')]
    public function updateUser($id, UserRepository $user, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $user->find($id);
        $form = $this->createForm(UserType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_admin');
        }
        return $this->renderForm('admin/updateUser.html.twig', [
            'user' => $dataid,
            'f' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete_user')]
    public function deleteUser($id, ManagerRegistry $managerRegistry, UserRepository $user): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $user->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/profile/delete/{id}', name: 'app_delete_user_profile')]
    public function deleteUserProfile($id, ManagerRegistry $managerRegistry, AuthorizationCheckerInterface $authChecker, UserRepository $user, DoctorRepository $doctor, PatientRepository $patient, RadiologistRepository $radiologist): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $user->find($id);

        if ($authChecker->isGranted('ROLE_DOCTOR')) {
            $doctorId = $doctor->findDoctorByUser($id);
            if ($doctorId !== null) {
                $em->remove($doctorId);
            }
        }
        if ($authChecker->isGranted('ROLE_PATIENT')) {
            $patientId = $patient->findPatientByUser($id);
            if ($patientId !== null) {
                $em->remove($patientId);
            }
        }
        if ($authChecker->isGranted('ROLE_RADIOLOGIST')) {
            $radiologistId = $radiologist->findradiologistByUser($id);
            if ($radiologistId !== null) {
                $em->remove($radiologistId);
            }
        }

        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }
}
