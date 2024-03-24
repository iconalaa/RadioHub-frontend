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
use App\Repository\DoctorRepository;
use App\Repository\PatientRepository;
use App\Repository\RadiologistRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\CompteRenduRepository;
use App\Security\PasswordHashing;

#[Route('/admin')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'app_admin', methods: ['GET', 'POST'])]
    public function admin(UserRepository $userRepo, CompteRenduRepository $compteRenduRepository): Response
    {
        $users = $userRepo->findAll();
        $patient = $userRepo->countUsersByRole("ROLE_PATIENT");
        $doctor = $userRepo->countUsersByRole("ROLE_DOCTOR");
        $radiologist = $userRepo->countUsersByRole("ROLE_RADIOLOGIST");

        $doneCount = $compteRenduRepository->countReportsByStatus(true);
        $notDoneCount = $compteRenduRepository->countReportsByStatus(false);

        return $this->render('admin/dashboard.html.twig', [
            'patient' => $patient,
            'doctor' => $doctor,
            'radiologist' => $radiologist,
            'allUsers' => $users,
            'done' => $doneCount,
            'notdone' => $notDoneCount,
        ]);
    }

    #[Route('/user', name: 'app_admin_user', methods: ['GET', 'POST'])]
    public function userDashboard(UserRepository $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger, PaginatorInterface $paginator): Response
    {
        $addUser = new User();
        $form = $this->createForm(UserType::class, $addUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
                $addUser->setBrochureFilename($newFilename);
            }else
                $addUser->setBrochureFilename('x');
            $addUser->setPassword(PasswordHashing::hashPassword($form->get('password')->getData()));
            $entityManager->persist($addUser);
            $entityManager->flush();
        }
        $users = $user->findAll();
        // ! Proof Start ------------
        $doctorProof = $user->findUsersByRole("ROLE_WAITING_DOCTOR");
        $radioProof = $user->findUsersByRole("ROLE_WAITING_RADIOLOGIST");
        // ! Proof End ------------
        $users = $user->findAll();
        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            5
        );
        $patient = $user->countUsersByRole("ROLE_PATIENT");
        $doctor = $user->countUsersByRole("ROLE_DOCTOR");
        $radiologist = $user->countUsersByRole("ROLE_RADIOLOGIST");

        return $this->render('admin/user.html.twig', [
            'form' => $form->createView(),
            // # Statistic start --------
            'patient' => $patient,
            'doctor' => $doctor,
            'radiologist' => $radiologist,
            // # Statistic end -----------
            'users' => $users,
            'notification' => $doctorProof,
            'notification_radio' => $radioProof,
        ]);
    }

    #[Route('/patient', name: 'app_admin_patient', methods: ['GET', 'POST'])]
    public function patientDashboard(PatientRepository $patient, Request $request, EntityManagerInterface $entityManager): Response
    {
        $addPatient = new Patient();
        $form = $this->createForm(PatientType::class, $addPatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRole = $addPatient->getUser()->getRoles();
            array_push($userRole, 'ROLE_PATIENT');
            $addPatient->getUser()->setRoles($userRole);
            $entityManager->persist($addPatient);
            $entityManager->flush();
        }
        $dataPatient = $patient->findAll();
        return $this->render('admin/patient/patient.html.twig', [
            'form' => $form->createView(),
            'patients' => $dataPatient,
        ]);
    }
    #[Route('/doctor', name: 'app_admin_doctor', methods: ['GET', 'POST'])]
    public function DoctorDashboard(DoctorRepository $Doctor, Request $request, EntityManagerInterface $entityManager): Response
    {
        $addDoctor = new Doctor();
        $form = $this->createForm(DoctorType::class, $addDoctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRole = $addDoctor->getUser()->getRoles();
            array_push($userRole, 'ROLE_DOCTOR');
            $addDoctor->getUser()->setRoles($userRole);
            $entityManager->persist($addDoctor);
            $entityManager->flush();
        }
        $dataDoctor = $Doctor->findAll();
        return $this->render('admin/doctor/doctor.html.twig', [
            'form' => $form->createView(),
            'doctors' => $dataDoctor,
        ]);
    }
    #[Route('/radiologist', name: 'app_admin_radiologist', methods: ['GET', 'POST'])]
    public function radiologistDashboard(RadiologistRepository $radiologist, Request $request, EntityManagerInterface $entityManager): Response
    {
        $addradiologist = new Radiologist();
        $form = $this->createForm(RadiologistType::class, $addradiologist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRole = $addradiologist->getUser()->getRoles();
            array_push($userRole, 'ROLE_RADIOLOGIST');
            $addradiologist->getUser()->setRoles($userRole);
            $entityManager->persist($addradiologist);
            $entityManager->flush();
        }
        $dataradiologist = $radiologist->findAll();
        return $this->render('admin/radiologist/radiologist.html.twig', [
            'form' => $form->createView(),
            'radiologists' => $dataradiologist,
        ]);
    }
    //! ------------------ PDF Controller -------------------------

    #[Route('/user/pdf/{id}', name: 'app_user_pdf')]
    public function downloadcertif($id, UserRepository $userRepo, DoctorRepository $docRepo, RadiologistRepository $radioRepo)
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'poppins');
        $pdfOptions->setIsRemoteEnabled(true);


        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $user = $userRepo->find($id);
        $doctor = $docRepo->findDoctorByUser($id);
        $radio = $radioRepo->findRadiologistByUser($id);
        $html = $this->renderView('admin/pdf.html.twig', [
            'user' => $user,
            'doc' => $doctor,
            'rad' => $radio,
        ]);
        $name = $user->getName();
        $lastName = $user->getLastname();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fichier = $name . '-' . $lastName . '.pdf';
        $dompdf->stream($fichier, ['Attachment' => true]);
        return new Response();
    }


    // ! ---------------- DELETE ---------------------------

    #[Route('/delete-patient/{id}', name: 'app_delete_patient')]
    public function deletePatient($id, ManagerRegistry $managerRegistry, PatientRepository $patient): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $patient->find($id);
        $em->remove($dataid);
        $userRole = $dataid->getUser()->getRoles();
        $index = array_search('ROLE_PATIENT', $userRole);
        array_splice($userRole, $index, 1);
        $dataid->getUser()->setRoles($userRole);
        $em->flush();

        return $this->redirectToRoute('app_admin_patient');
    }
    #[Route('/delete-doctor/{id}', name: 'app_delete_doctor')]
    public function deletedoctor($id, ManagerRegistry $managerRegistry, DoctorRepository $doctor): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $doctor->find($id);
        $em->remove($dataid);
        $userRole = $dataid->getUser()->getRoles();
        $index = array_search('ROLE_DOCTOR', $userRole);
        array_splice($userRole, $index, 1);
        $dataid->getUser()->setRoles($userRole);
        $em->flush();

        return $this->redirectToRoute('app_admin_doctor');
    }

    #[Route('/delete-radiologist/{id}', name: 'app_delete_radiologist')]
    public function deleteRadiologist($id, ManagerRegistry $managerRegistry, RadiologistRepository $radiologist): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $radiologist->find($id);
        $em->remove($dataid);
        $userRole = $dataid->getUser()->getRoles();
        $index = array_search('ROLE_RADIOLOGIST', $userRole);
        array_splice($userRole, $index, 1);
        $dataid->getUser()->setRoles($userRole);
        $em->flush();

        return $this->redirectToRoute('app_admin_radiologist');
    }

    #[Route('/delete/{id}', name: 'app_delete_user')]
    public function deleteUser($id, ManagerRegistry $managerRegistry, UserRepository $userRepo, DoctorRepository $doctorRepo, PatientRepository $patientRepo, RadiologistRepository $radiologistRepo): Response
    {
        $em = $managerRegistry->getManager();
        $user = $userRepo->find($id);
        // ! -------- Doctor ------------------
        $doctor = $doctorRepo->findDoctorByUser($id);
        if ($doctor !== null) {
            $em->remove($doctor);
        }
        // ! -------- Patient ------------------
        $patient = $patientRepo->findPatientByUser($id);
        if ($patient !== null) {
            $em->remove($patient);
        }
        // ! -------- Radiologist ------------------
        $radiologist = $radiologistRepo->findRadiologistByUser($id);
        if ($radiologist !== null) {
            $em->remove($radiologist);
        }

        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_admin_user');
    }




    // ! --------------------- UPDATE ---------------------------------

    #[Route('/update/{id}', name: 'app_update_user')]
    public function updateUser($id, UserRepository $user, ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger): Response
    {
        $em = $managerRegistry->getManager();
        $userEmpty = new User;

        $dataid = $user->find($id);

        $userEmpty->setName($dataid->getName());
        $userEmpty->setLastname($dataid->getLastname());
        $userEmpty->setEmail($dataid->getEmail());

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
            $dataid->setName($userEmpty->getName());
            $dataid->setLastname($userEmpty->getLastname());
            $dataid->setEmail($userEmpty->getEmail());
            $dataid->setDateBirth($userEmpty->getDateBirth());
            $dataid->setGender($userEmpty->getgender());

            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_admin_user');
        }
        return $this->renderForm('admin/updateUser.html.twig', [
            'user' => $dataid,
            'f' => $form
        ]);
    }

    #[Route('/update-patient/{id}', name: 'app_update_patient')]
    public function updatePatient($id, PatientRepository $patient, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $patient->find($id);
        $form = $this->createForm(PatientType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_admin_patient');
        }
        return $this->renderForm('admin/patient/update.html.twig', [
            'user' => $dataid,
            'f' => $form
        ]);
    }
    #[Route('/update-doctor/{id}', name: 'app_update_doctor')]
    public function updateDoctor($id, DoctorRepository $doctor, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $doctor->find($id);
        $form = $this->createForm(DoctorType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_admin_doctor');
        }
        return $this->renderForm('admin/doctor/update.html.twig', [
            'user' => $dataid,
            'form' => $form
        ]);
    }
    #[Route('/update-radiologist/{id}', name: 'app_update_radiologist')]
    public function updateRadiologist($id, RadiologistRepository $radiologist, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $radiologist->find($id);
        $form = $this->createForm(RadiologistType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_admin_radiologist');
        }
        return $this->renderForm('admin/radiologist/update.html.twig', [
            'user' => $dataid,
            'form' => $form
        ]);
    }

    // ! --------------------- Features ---------------------------------
    // # ----------------- Doctor -----------------

    #[Route('/proof-doctor/{id}', name: 'app_proof', methods: ['GET', 'POST'])]
    public function proof($id, UserRepository $userRepo, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $user = $userRepo->find($id);
        $user->setRoles(["ROLE_DOCTOR"]);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_admin_user');
    }
    #[Route('/trash-doctor/{id}', name: 'app_trash', methods: ['GET', 'POST'])]
    public function trash($id, UserRepository $userRepo, DoctorRepository $doctorRepo, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $user = $userRepo->find($id);
        $doctor = $doctorRepo->findDoctorByUser($id);
        $user->setRoles([]);
        $em->persist($user);
        $em->remove($doctor);
        $em->flush();
        return $this->redirectToRoute('app_admin_user');
    }
    // # ----------------- Radiologist -----------------

    #[Route('/proof-radio/{id}', name: 'app_proof_radio', methods: ['GET', 'POST'])]
    public function proofRad($id, UserRepository $userRepo, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $user = $userRepo->find($id);
        $user->setRoles(["ROLE_RADIOLOGIST"]);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_admin_user');
    }

    #[Route('/trash-radio/{id}', name: 'app_trash_radio', methods: ['GET', 'POST'])]
    public function trashRad($id, UserRepository $userRepo, RadiologistRepository $radiologistRepo, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $user = $userRepo->find($id);
        $radiologist = $radiologistRepo->findRadiologistByUser($id);
        $user->setRoles([]);
        $em->persist($user);
        $em->remove($radiologist);
        $em->flush();
        return $this->redirectToRoute('app_admin_user');
    }
}
