<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\User;
use App\Form\CompteRenduType;
use App\Repository\ReportRepository;
use App\Repository\PrescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ReportController extends AbstractController
{

    #[Route('/edit/{id}', name: 'app_Report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Report $Report, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompteRenduType::class, $Report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission
            $entityManager->flush();

            $this->addFlash('success', 'Compte rendu updated successfully.');

            return $this->redirectToRoute('app_admin_report');
        }

        return $this->render('admin/Report/edit.html.twig', [
            'Report' => $Report,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/report', name: 'app_admin_report')]
    public function index(ReportRepository $ReportRepository): Response
    {
        return $this->render('admin/report.html.twig', [
            'Reports' => $ReportRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_Report_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $Report = new Report();
        $form = $this->createForm(CompteRenduType::class, $Report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Report);
            $entityManager->flush();

            return $this->redirectToRoute('app_Report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/Report/new.html.twig', [
            'Report' => $Report,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_Report_show', methods: ['GET'])]
    public function show(Report $Report): Response
    {
        return $this->render('admin/Report/show.html.twig', [
            'Report' => $Report,
        ]);
    }


    #[Route('/{id}', name: 'app_Report_delete', methods: ['POST'])]
    public function delete(Request $request, Report $Report, EntityManagerInterface $entityManager, PrescriptionRepository $pres): Response
    {
        if ($this->isCsrfTokenValid('delete' . $Report->getId(), $request->request->get('_token'))) {


            $del_pres = $pres->findOneBy(["Report" => $Report]);
            $entityManager->remove($del_pres);
            $entityManager->flush();
            $entityManager->remove($Report);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_report', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export/gg', name: 'app_export_excel')]
    public function export(ReportRepository $ReportRepository): Response
    {
        // Fetch data from the Report entity
        $Reports = $ReportRepository->findBy(['isEdited' => true]);

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Interpretation Med')
            ->setCellValue('C1', 'Interpretation Rad')
            ->setCellValue('D1', 'Doctor Name')
            ->setCellValue('E1', 'Date');

        // Populate data rows
        $row = 2;
        foreach ($Reports as $Report) {
            // Retrieve the associated Doctor entity
            $doctor = $Report->getDoctor();
            // Get the user associated with the doctor to access the name
            $user = $doctor ;
            // Get the doctor's name
            $doctorName = $user ? $user->getName() : '';

            $sheet->setCellValue('A' . $row, $Report->getId())
                ->setCellValue('B' . $row, $Report->getInterpretationMed())
                ->setCellValue('C' . $row, $Report->getInterpretationRad())
                ->setCellValue('D' . $row, $doctorName)
                ->setCellValue('E' . $row, $Report->getDate() ? $Report->getDate()->format('Y-m-d') : ''); // Assuming Date is a DateTime object
            $row++;
        }

        // Create a writer and save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $filename = 'Reports.xlsx';
        $writer->save($filename);

        // Set headers for Excel file download
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Send the file as response
        $response->setContent(file_get_contents($filename));

        // Delete the file after sending
        unlink($filename);

        return $response;
    }
}
