<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\RadType;
use App\Repository\ReportRepository;
use App\Repository\DoctorRepository;
use App\Repository\ImageRepository;
use App\Repository\RadiologistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class RadioRenduController extends AbstractController
{
    #[Route('/radio/{id}', name: 'app_radio', methods: ['GET', 'POST'])]
    public function new($id,Request $request, EntityManagerInterface $entityManager, DoctorRepository $medrepo, ImageRepository $imagesRepo, RadiologistRepository $repoRad,ReportRepository $cm): Response
    {

        // Retrieve the currently logged-in user
        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$user) {
            throw new \LogicException('No user is logged in.');
        }
        // Retrieve the associated doctor entity using the DoctorRepository
        $radiologist = $repoRad->findOneBy(['user' => $user]);

        // Check if the user is a doctor
        if (!$radiologist) {
            throw new \LogicException('Logged-in user is not associated with any doctor.');
        }


        $o=$cm->findOneBy(["image"=> $id]);
       if($o !=null)
    {

        return $this->render('radio/error.html.twig');
    }
        $Report = new Report();
        $form = $this->createForm(RadType::class, $Report);
        $form->handleRequest($request);
       $image= $imagesRepo->findOneBy(['id'=>$id]);
        if ($form->isSubmitted() && $form->isValid()) {
            $Report->setImage($image);
            $entityManager->persist($Report);
            $entityManager->flush();

            return $this->redirectToRoute('app_image');
        }

        $medecins = $medrepo->findAll();
        $images = $imagesRepo->findImagesWithoutCompteRendu();


        return $this->render('radio/radio.html.twig', [
            'medecins' => $medecins,
            'idimage' => $id,
            'form' => $form->createView(), // Pass the form to the template
        ]);
    }
}
