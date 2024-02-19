<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Entity\Medecin;
use App\Repository\MedecinRepository;

use App\Form\CompteRenduType1;
use App\Repository\CompteRenduRepository;
use App\Repository\DoctorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MedRenduController extends AbstractController
{
    #[Route('/add-interprétation/{id}', name: 'add_interprétation')]
    public function addInterpretation(Request $request, CompteRendu $compteRendu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompteRenduType1::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteRendu->setIsEdited(true); // Mark the compte rendu as edited
            $entityManager->flush();

            // Redirect back to the 'app_med' route with the updated compte rendu ID
            return $this->redirectToRoute('app_med', ['updated_id' => $compteRendu->getId()]);
        }

        return $this->render('med/update_compte_rendu.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/med', name: 'app_med', methods: ['GET'])]
    public function index(CompteRenduRepository $rendurepo, Request $request,DoctorRepository $repomed): Response
    {
        // Retrieve the updated compte rendu ID from the request parameters
        $updatedId = $request->query->get('updated_id');

        $id = 1; // Assuming this is the ID of the logged-in medecin

        // Retrieve the list of compte rendus for the logged-in medecin
        $compteRendus = $rendurepo->findBy(['id_doctor' => $id, 'isEdited' => false]);
        $compteRendusdone  = $rendurepo->findBy(['id_doctor' => $id, 'isEdited' => True]);
        $med= $repomed->findBy(['id' => $id]);

        // Filter out the updated compte rendu from the list if it exists
        if ($updatedId !== null) {
            $compteRendus = array_filter($compteRendus, function ($compteRendu) use ($updatedId) {
                return $compteRendu->getId() != $updatedId;
            });
        }

        return $this->render('med/med.html.twig', [
            'compteRendus' => $compteRendus,
            'medname' => $med,
            'done' =>$compteRendusdone,

            
        ]);
    }
}
