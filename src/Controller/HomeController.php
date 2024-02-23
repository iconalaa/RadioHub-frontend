<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DoctorRepository;
use App\Repository\PatientRepository;
use App\Repository\RadiologistRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function frontOffice(): Response
    {
        return $this->render('home/home.html.twig', []);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('home/profile.html.twig', []);
    }

    #[Route('/settings/{id}', name: 'app_settings')]
    public function settingsUser($id,UserPasswordHasherInterface $userPasswordHasher, UserRepository $user, ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger): Response
    {
        $em = $managerRegistry->getManager();
        $userEmpty = new User;
        $dataid = $user->find($id);
        $userEmpty->setName($dataid->getName());
        $userEmpty->setLastname($dataid->getLastname());
        $userEmpty->setEmail($dataid->getEmail());
        $userEmpty->setGender($dataid->getGender());
        $userEmpty->setDateBirth($dataid->getDateBirth());

        $form = $this->createForm(UserType::class, $userEmpty);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

            $brochureFile = $form->get('brochureFilename')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $dataid->setBrochureFilename($newFilename);
            }
            $dataid->setPassword(
                $userPasswordHasher->hashPassword(
                    $dataid,
                    $form->get('password')->getData()
                )
            );

            $dataid->setName($userEmpty->getName());
            $dataid->setLastname($userEmpty->getLastname());
            $dataid->setDateBirth($userEmpty->getDateBirth());
            $dataid->setGender($userEmpty->getgender());

            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_profile');
        }
        return $this->renderForm('home/settings.html.twig', [
            'f' => $form
        ]);
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
        return $this->redirectToRoute('app_login');
    }
}
