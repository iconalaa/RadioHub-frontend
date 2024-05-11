<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\MedType;
use App\Repository\ReportRepository;
use App\Repository\UserRepository;
use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Knp\Component\Pager\PaginatorInterface;


class DocRenduController extends AbstractController
{
    #[Route('/add_decision/{id}', name: 'add_decision')]
    public function addInterpretation(Request $request, Report $Report, EntityManagerInterface $entityManager, UserRepository $repoMed): Response
    {
        // Retrieve the currently logged-in user
        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$user) {
            throw new \LogicException('No user is logged in.');
        }
        // Retrieve the associated doctor entity using the DoctorRepository
        $doctor = $user;

        // Check if the user is a doctor
       

        $form = $this->createForm(MedType::class, $Report);
        $form->handleRequest($request);
        $idimage = $Report->getImage()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            // Convert string date to DateTime object
            $date = $form->get('date')->getData();

            $Report->setDate($date);
            $Report->setIsEdited(true); // Mark the compte rendu as edited
            $entityManager->flush();

            // Redirect back to the 'app_doctor' route with the updated compte rendu ID
            return $this->redirectToRoute('app_doctor', ['updated_id' => $Report->getId()]);
        }

        return $this->render('med/update.html.twig', [
            'form' => $form->createView(),
            'idimage' => $idimage,
        ]);
    }

    #[Route('/doctor', name: 'app_doctor', methods: ['GET'])]
    public function index(Request $request, ReportRepository $repoCompteendu, UserRepository $repoMed, PrescriptionRepository $prescription, PaginatorInterface $paginator): Response
    {
        // Retrieve the currently logged-in user
        /** @var UserInterface $user */
        $user = $this->getUser();

        // Check if the user is logged in
        if (!$user) {
            throw new \LogicException('No user is logged in.');
        }

        // Retrieve the associated doctor entity using the DoctorRepository
        $doctor =  $user ;
        $prescriptions = $prescription->findAll();

       

        // Retrieve the ID of the associated doctor
        $doctorId = $doctor->getId();

        // Now you have the ID of the associated doctor, you can use it for further processing
        // Retrieve the list of compte rendus for the logged-in doctor
        $Reports = $repoCompteendu->findBy(['doctor' => $doctorId, 'isEdited' => false]);
        $Reportsdone = $repoCompteendu->findBy(['doctor' => $doctorId, 'isEdited' => true]);

        // Paginate the done compte rendus
        $pagination = $paginator->paginate(
            $Reportsdone,
            $request->query->getInt('page', 1), // Get page number from the request, default to 1
            1 // Items per page
        );

        return $this->render('med/med.html.twig', [
            'Reports' => $Reports,
            'done' => $pagination, // Pass the paginated data to the template
            'prep' => $prescriptions,
        ]);
    }



    #[Route('/generate-pdf/{id}', name: 'generate_pdf')]
    public function generatePdfAction(Report $Report): Response
    {
        // Get absolute file path to the logo image
        $idimage = $Report->getImage()->getId();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/images/' . $idimage . '.png';

        // Check if the logo image exists
        if (file_exists($imagePath)) {
            // Get the logo image as base64 encoded string
            $imageData = base64_encode(file_get_contents($imagePath));
        } else {
            // Provide a fallback image or handle the case where the logo image is missing
            $imageData = ''; // You may set a default image here if needed
        }

        // Render the PDF template with the Report data
        $html = $this->renderView('pdf/report_template.html.twig', [
            'report' => $Report,
            'imageData' => $imageData, // Pass the base64-encoded image data to the template
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
        $pdfFileName = sprintf('compte_rendu_%s.pdf', $Report->getId());

        // Stream the PDF to the client
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"',
        ]);
    }

    
}
