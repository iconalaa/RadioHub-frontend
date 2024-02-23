<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\CompteRenduRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class PrescriptionController extends AbstractController
{
    #[Route('/new/{compteRenduId}', name: 'app_prescription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompteRenduRepository $compteRenduRepository, $compteRenduId, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $compteRendu = $compteRenduRepository->find($compteRenduId);
        if (!$compteRendu) {
            throw $this->createNotFoundException('Compte rendu not found');
        }

        $prescription = new Prescription();
        $prescription->setCompterendu($compteRendu);

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
}
