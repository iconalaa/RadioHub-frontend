<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\RadType;
use App\Repository\ReportRepository;
use App\Repository\DoctorRepository;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class RadioRenduController extends AbstractController
{
    #[Route('/radio/{id}', name: 'app_radio', methods: ['GET', 'POST'])]
    public function new($id,Request $request, EntityManagerInterface $entityManager, UserRepository $medrepo, ImageRepository $imagesRepo, UserRepository $repoRad,ReportRepository $cm): Response
    {

        // Retrieve the currently logged-in user
        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$user) {
            throw new \LogicException('No user is logged in.');
        }
        // Retrieve the associated doctor entity using the DoctorRepository
        $radiologist =$user;

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



        $doctors=$medrepo->findDoctors();
        $form = $this->createForm(RadType::class, $Report, [
            'doctors' => $doctors,
        ]);
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
