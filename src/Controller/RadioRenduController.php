<?php

namespace App\Controller;

use App\Entity\CompteRendu;

use App\Form\RadType;
use App\Repository\ImagesRepository;
use App\Repository\MedecinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class RadioRenduController extends AbstractController
{
    #[Route('/radio', name: 'app_radio', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MedecinRepository $medrepo, ImagesRepository $imagesRepo): Response
    {
        $compteRendu = new CompteRendu();
        $form = $this->createForm(RadType::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compteRendu);
            $entityManager->flush();

            return $this->redirectToRoute('app_radio');
        }

        $medecins = $medrepo->findAll();
        $images = $imagesRepo->findImagesWithoutCompteRendu(); 
        
        

        return $this->render('radio/radio.html.twig', [
            'medecins' => $medecins,
            'images' => $images,
            'form' => $form->createView(), // Pass the form to the template
        ]);
    }
}