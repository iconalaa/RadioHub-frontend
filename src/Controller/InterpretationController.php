<?php

namespace App\Controller;

use App\Entity\Interpretation;
use App\Form\InterpretationType;
use App\Repository\ImageRepository;
use App\Repository\InterpretationRepository;
use App\Repository\RadiologistRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class InterpretationController extends AbstractController
{


    #[Route('/interpretatin/{id}', name: 'add_interpretation')]
    public function add($id,Request $request,ManagerRegistry $em,ImageRepository $rep,Security $security,RadiologistRepository $reprad): Response
    {


// Create a new instance of the form class

        $inter=new Interpretation();
        $form = $this->createForm(InterpretationType::class,$inter);

        // Handle form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Extract data from the form

            // Perform any necessary processing with the form data
            $inter->setImage($rep->find($id));
            $user = $security->getUser();
            $rad=$reprad->findOneBy(['user'=> $user]);
            $inter->setRadiologist($rad);
            $timestamp = time();
            $currentDate = gmdate('Y-m-d', $timestamp);
            $inter->setSendat($currentDate);

            // For example, save data to the database

                $em->getManager()->persist($inter);
                $em->getManager()->flush();
            // Redirect the user to another page
            return $this->redirectToRoute('inter');
        }

        // Render the form template
        return $this->renderForm('interpretation/add.html.twig', [
            'form' => $form,
        ]);
    }



        #[Route('/interedit/{id}', name: 'editinterpretation')]
    public function edit($id,Request $request,ManagerRegistry $em, InterpretationRepository $rep,Security $security,RadiologistRepository $reprad): Response
    {
// Create a new instance of the form class
        $inter=$rep->find($id);
        $form = $this->createForm(InterpretationType::class,$inter);

        // Handle form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Extract data from the form

            // Perform any necessary processing with the form data

            $timestamp = time();
            $currentDate = gmdate('Y-m-d', $timestamp);
            $inter->setSendat($currentDate);
            // For example, save data to the database

            $em->getManager()->persist($inter);
            $em->getManager()->flush();
            // Redirect the user to another page
            return $this->redirectToRoute('inter');
        }

        // Render the form template
        return $this->renderForm('interpretation/edit.html.twig', [
            'form' => $form,"interpretation"=>$inter
        ]);
    }















    #[Route('/delete/{id}', name: 'deleteinter')]
    public function delete($id,ManagerRegistry $em,InterpretationRepository $rep,Security $security,RadiologistRepository $reprad): Response
    {

        $inter=$rep->find($id);

        $em->getManager()->remove($inter);
        $em->getManager()->flush();

        return $this->redirectToRoute('inter');
    }


    #[Route('/inter', name: 'inter')]
    public function inter(InterpretationRepository $rep,Security $security,RadiologistRepository $reprad): Response
    {
        $user = $security->getUser();

        $rad=$reprad->findOneBy(['user'=> $user]);

        // Assuming there's a relationship between Radiologist and Interpretation entities,
        // adjust the query to retrieve Interpretation entities associated with the given Radiologist
        $inter = $rep->findBy(['radiologist' => $rad]);

        return $this->render('interpretation/index.html.twig', [
            'inter'=> $inter
        ]);
    }

    #[Route('/feedbacks/{id}', name: 'feedbacks')]
    public function show($id,InterpretationRepository $rep,Request $request,ManagerRegistry $em,Security $security,RadiologistRepository $reprad): Response
    {
        $feeds=$rep->findBy(['image'=>$id]);
        return  $this->render('image/feeds.html.twig',['feeds'=>$feeds]);

    }
}
