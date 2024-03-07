<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Entity\User;
use App\Form\CompteRenduType;
use App\Repository\CompteRenduRepository;
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

    #[Route('/{id}/edit', name: 'app_compte_rendu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompteRendu $compteRendu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompteRenduType::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission
            $entityManager->flush();

            $this->addFlash('success', 'Compte rendu updated successfully.');

            return $this->redirectToRoute('app_admin_report');
        }

        return $this->render('admin/compte_rendu/edit.html.twig', [
            'compte_rendu' => $compteRendu,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/report', name: 'app_admin_report')]
    public function index(CompteRenduRepository $compteRenduRepository): Response
    {
        return $this->render('admin/report.html.twig', [
            'compte_rendus' => $compteRenduRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_compte_rendu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compteRendu = new CompteRendu();
        $form = $this->createForm(CompteRenduType::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compteRendu);
            $entityManager->flush();

            return $this->redirectToRoute('app_compte_rendu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/compte_rendu/new.html.twig', [
            'compte_rendu' => $compteRendu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_rendu_show', methods: ['GET'])]
    public function show(CompteRendu $compteRendu): Response
    {
        return $this->render('admin/compte_rendu/show.html.twig', [
            'compte_rendu' => $compteRendu,
        ]);
    }


    #[Route('/{id}', name: 'app_compte_rendu_delete', methods: ['POST'])]
    public function delete(Request $request, CompteRendu $compteRendu, EntityManagerInterface $entityManager, PrescriptionRepository $pres): Response
    {
        if ($this->isCsrfTokenValid('delete' . $compteRendu->getId(), $request->request->get('_token'))) {


            $del_pres = $pres->findOneBy(["compterendu" => $compteRendu]);
            $entityManager->remove($del_pres);
            $entityManager->flush();
            $entityManager->remove($compteRendu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_report', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export/gg', name: 'app_export_excel')]
    public function export(CompteRenduRepository $compteRenduRepository): Response
    {
        // Fetch data from the CompteRendu entity
        $compteRendus = $compteRenduRepository->findBy(['isEdited' => true]);

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
        foreach ($compteRendus as $compteRendu) {
            // Retrieve the associated Doctor entity
            $doctor = $compteRendu->getIdDoctor();
            // Get the user associated with the doctor to access the name
            $user = $doctor ? $doctor->getUser() : null;
            // Get the doctor's name
            $doctorName = $user ? $user->getName() : '';

            $sheet->setCellValue('A' . $row, $compteRendu->getId())
                ->setCellValue('B' . $row, $compteRendu->getInterpretationMed())
                ->setCellValue('C' . $row, $compteRendu->getInterpretationRad())
                ->setCellValue('D' . $row, $doctorName)
                ->setCellValue('E' . $row, $compteRendu->getDate() ? $compteRendu->getDate()->format('Y-m-d') : ''); // Assuming Date is a DateTime object
            $row++;
        }

        // Create a writer and save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $filename = 'compte_rendus.xlsx';
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
