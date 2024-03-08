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
use Dompdf\Dompdf;

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







    #[Route('/generate-pdfinter/{id}', name: 'generate_pdfinter')]
    public function generatePdfinter($id,ImageRepository $im): Response
    {  $image= $im->findOneBy(["id"=>$id]);
        // Fetch interpretations related to the image ID from your database
        $interpretations = $this->getDoctrine()->getRepository(Interpretation::class)->findBy(['image' => $image]);
    
        // Initialize an empty array to hold interpretation data
        $interpretationData = [];
    
        // Loop through fetched interpretations and extract necessary data
        foreach ($interpretations as $interpretation) {
            // Extract data for each interpretation
            $interpretationData[] = [
                'id' => $interpretation->getId(),
                'content' => $interpretation->getInterpretation(),
                'desc'=> $interpretation->getDescription(),
                // Add more fields as needed
            ];
        }
        // Render the PDF template with the interpretation data
        $html = $this->renderView('pdf/interpretation_list_template.html.twig', [
            'interpretations' => $interpretationData,
            'idimage'=>$id

        ]);
    
        // Instantiate Dompdf
        $dompdf = new Dompdf();
    
        // Load HTML content
        $dompdf->loadHtml($html);
    
        // Set paper size and orientation (optional)
        $dompdf->setPaper('A4', 'portrait');
    
        // Render the HTML as PDF
        $dompdf->render();
    
        // Generate PDF file name (optional)
        $pdfFileName = sprintf('interpretations_for_image_%s.pdf', $id);
    
        // Stream the PDF to the client
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"',
        ]);
    }






}
