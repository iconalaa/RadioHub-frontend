<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Form\MedType;
use App\Repository\CompteRenduRepository;
use App\Repository\DoctorRepository;
use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;


class DocRenduController extends AbstractController
{
    #[Route('/add_interpretation/{id}', name: 'add_interpretation')]
    public function addInterpretation(Request $request, CompteRendu $compteRendu, EntityManagerInterface $entityManager, DoctorRepository $repoMed): Response
    {
        // Retrieve the currently logged-in user
        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$user) {
            throw new \LogicException('No user is logged in.');
        }
        // Retrieve the associated doctor entity using the DoctorRepository
        $doctor = $repoMed->findOneBy(['user' => $user]);

        // Check if the user is a doctor
        if (!$doctor) {
            throw new \LogicException('Logged-in user is not associated with any doctor.');
        }

        $form = $this->createForm(MedType::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Convert string date to DateTime object
            $dateString = $form->get('date')->getData();
            $date = \DateTime::createFromFormat('Y-m-d', $dateString);
            $compteRendu->setDate($date);

            $compteRendu->setIsEdited(true); // Mark the compte rendu as edited
            $entityManager->flush();

            // Redirect back to the 'app_doctor' route with the updated compte rendu ID
            return $this->redirectToRoute('app_doctor', ['updated_id' => $compteRendu->getId()]);
        }

        return $this->render('med/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/doctor', name: 'app_doctor', methods: ['GET'])]
    public function index(CompteRenduRepository $repoCompteendu, DoctorRepository $repoMed,PrescriptionRepository $prescription): Response
    {
        // Retrieve the currently logged-in user
        /** @var UserInterface $user */
        $user = $this->getUser();

        // Check if the user is logged in
        if (!$user) {
            throw new \LogicException('No user is logged in.');
        }

        // Retrieve the associated doctor entity using the DoctorRepository
        $doctor = $repoMed->findOneBy(['user' => $user]);
        $prescriptions=$prescription->findAll();
        // Check if the user is a doctor
        if (!$doctor) {
            throw new \LogicException('Logged-in user is not associated with any doctor.');
        }


        // Retrieve the ID of the associated doctor
        $doctorId = $doctor->getId();


        // Now you have the ID of the associated doctor, you can use it for further processing
        // Retrieve the list of compte rendus for the logged-in doctor
        $compteRendus = $repoCompteendu->findBy(['id_doctor' => $doctorId, 'isEdited' => false]);
        $compteRendusdone = $repoCompteendu->findBy(['id_doctor' => $doctorId, 'isEdited' => true]);

        return $this->render('med/med.html.twig', [
            'compteRendus' => $compteRendus,
            'done' => $compteRendusdone,
            'prep' => $prescriptions,

        ]);
    }

    #[Route('/generate-pdf/{id}', name: 'generate_pdf')]
    public function generatePdfAction(CompteRendu $compteRendu): Response
    {
        // Render the PDF template with the CompteRendu data
        $html = $this->renderView('pdf/report_template.html.twig', [
            'compteRendu' => $compteRendu,
        ]);

        // Instantiate Dompdf
        $dompdf = new Dompdf();

        // Load HTML content
        $dompdf->loadHtml($html);

        // Set paper size and orientation (optional)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Generate PDF file name (optional)
        $pdfFileName = sprintf('compte_rendu_%s.pdf', $compteRendu->getId());

        // Stream the PDF to the client
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"',
        ]);
    }
}
