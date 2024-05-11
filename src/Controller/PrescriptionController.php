<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PrescriptionRepository;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\PrescriptionType;
use App\Entity\Prescription;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dompdf\Dompdf;
use Dompdf\Options;




#[Route('/prescription')]
class PrescriptionController extends AbstractController
{
    #[Route('/new/{reportId}', name: 'app_prescription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, reportRepository $reportRepository, $reportId, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $report = $reportRepository->find($reportId);
        if (!$report) {
            throw $this->createNotFoundException('Compte rendu not found');
        }

        $prescription = new Prescription();
        $prescription->setreport($report);

        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $signatureFile = $form->get('signatureFilename')->getData();
            if ($signatureFile) {
                $originalFilename = pathinfo($signatureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $signatureFile->guessExtension();
                try {
                    $signatureFile->move(
                        $this->getParameter('signature_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                }
                $prescription->setSignatureFilename($newFilename);
            }

            $entityManager->persist($prescription);
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription/new.html.twig', [
            'prescription' => $prescription,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show', name: 'app_prescription_show', methods: ['GET'])]
    public function show(PrescriptionRepository $PrescriptionRepository): Response
    {
        return $this->render('prescription/test.html.twig', [
            'prescription' => $PrescriptionRepository->findAll(),
        ]);
    }

    #[Route('/generate_prescription/{id}', name: 'generate_prescription')]
    public function generatePdf(Request $request, Prescription $prescription): Response
    {
        // Get related report
        $report = $prescription->getreport();

        // Get absolute file path to the logo image
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/uploads/signatures/logo.png';

        // Check if the logo image exists
        if (file_exists($logoPath)) {
            // Get the logo image as base64 encoded string
            $logoData = base64_encode(file_get_contents($logoPath));
        } else {
            // Provide a fallback image or handle the case where the logo image is missing
            $logoData = '';
        }

        // Render PDF template with prescription data and logo image data
        $pdf = $this->renderView('pdf/prescription_template.html.twig', [
            'prescription' => $prescription,
            'imageData' => $this->getBase64ImageData($prescription),
            'logoData' => $logoData, // Pass the logo data to the template
            'report' => $report,
        ]);

        // Create PDF options
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Instantiate Dompdf with options
        $dompdf = new Dompdf($options);

        // Load HTML content into Dompdf
        $dompdf->loadHtml($pdf);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF (output to browser)
        $dompdf->render();

        // Generate PDF file name
        $fileName = 'prescription_' . $prescription->getId() . '.pdf';

        // Send PDF as response
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }

    private function getBase64ImageData(Prescription $prescription): string
    {
        if ($prescription->getSignatureFilename()) {
            $imagePath = $this->getParameter('signature_directory') . '/' . $prescription->getSignatureFilename();
            $imageData = base64_encode(file_get_contents($imagePath));
            return $imageData;
        } else {
            return ''; // Return an empty string if no signature image is available
        }
    }
}
