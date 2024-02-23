<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordonnance;
use App\Form\OrdonnanceType;
use App\Repository\OrdonnanceRepository;
use App\Repository\CompteRenduRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class PrescriptionController extends AbstractController
{
    #[Route('/new/{compteRenduId}', name: 'app_prescription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompteRenduRepository $compteRenduRepository, $compteRenduId, EntityManagerInterface $entityManager): Response
    {
        $compteRendu = $compteRenduRepository->find($compteRenduId);
        if (!$compteRendu) {
            throw $this->createNotFoundException('Compte rendu not found');
        }

        $ordonnance = new Ordonnance();
        $ordonnance->setCompterendu($compteRendu); // Associate the CompteRendu with the Ordonnance

        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ordonnance);
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ordonnance/new.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }
}
