<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Form\CompteRenduType1;
use App\Repository\CompteRenduRepository;
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

            $entityManager->flush();

            return $this->redirectToRoute('app_med');
        }

        return $this->render('med/update_compte_rendu.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/med', name: 'app_med', methods: ['GET'])]
    public function index(CompteRenduRepository $rendurepo) : Response
    {


        $id=1;

        $compteRendus=$rendurepo->findBy(['id_medecin' => $id]);


        return $this->render('med/med.html.twig', [
            'compteRendus' => $compteRendus,
        ]);
    }



    
    


    


}
