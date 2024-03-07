<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\RendezVous;
use App\Form\RendezVousType1;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Services\MailService;

#[Route('/rendezVous')]
class RendezVousController extends AbstractController
{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $rendezVous = $rendezVousRepository->findAll();
        $rendezVous = $paginator->paginate(
            $rendezVous,
            $request->query->getInt('page', 1),
            //limit per page
            3
        );



        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' =>
            $rendezVous,
        ]);
    }

    #[Route('/booked', name: 'app_booked_appointment', methods: ['GET', 'POST'])]
    public function booked(Request $request): Response
    {
        // Handle POST request logic if needed

        return $this->render('rendez_vous/booked.html.twig');
    }
    #[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $entityManager, MailService $mailService): Response
    {
        $rendezVou = new RendezVous();
        $form = $this->createForm(RendezVousType1::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->getManager()->persist($rendezVou);
            $entityManager->getManager()->flush();
            // Envoi de l'e-mail ici après avoir persisté la nouvelle proposition
            $mailService->sendEmail();


            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/new.html.twig', [

            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, ManagerRegistry $entityManager): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->getManager()->flush();

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, ManagerRegistry $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rendezVou->getId(), $request->request->get('_token'))) {
            $entityManager->getManager()->remove($rendezVou);
            $entityManager->getManager()->flush();
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }





    //////////////////////search ///////////////////////////////////////

    /*  #[Route('/{id}/search', name: 'app_appointment_search', methods: ['GET'])]
    public function search(Request $request, RendezVousRepository $RendezVousRepository): JsonResponse
    {
        $searchTerm = $request->query->get('search');
        $rendez_vous = $RendezVousRepository->findBySearchTerm($searchTerm);

        $data = [];
        foreach ($rendez_vous as $rendez_vous) {
            $data[] = [
                'id ' => $rendez_vous->getId(),
                'nomPatient' => $rendez_vous->getnomPatient(),
                'prenomPatient' => $rendez_vous->getprenomPatient(),
                'mailPatient' => $rendez_vous->getMailPatient(),
                'dateRV' => $rendez_vous->getdateRV(),
               
            ];
        }

        return new JsonResponse(['appointments' => $data]);
    }*/



    ////////////////////////////////////////////////




    /*public function rendez_vous (Request $request, ManagerRegistry $entityManager, LoggerInterface $logger, RendezVousRepository $rdvrepo): Response
    {
        $searchValue = $request->request->get('searchValue');
        $logger->info('Search Value: ' . $searchValue);
    
        if ($searchValue) {
            // If there's a search value, perform a filtered search
            /*$appointments = $entityManager->getRepository(RendezVous::class)-> findBySearchValue ($searchValue);*/
    /* $appointments= $rdvrepo->findBySearchValue($searchValue);
            //en principe hakka tekhdem
            //kima nkoulou tamel instance mel repo, w menha heya temchi tjib el method mtek w thot fiha el parameter eli adytou  hedha maneha
        } else {
            // If no search value, fetch all users
            $appointments = $entityManager->getRepository(RendezVous::class)->findAll();
        }
    
        // Render the entire page, including the layout
        return $this->render('rendez_vous/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }
    #[Route('/appointmentss/search', name: 'app_appointments_search')]
           public function searchAppointments(Request $request, ManagerRegistry $entityManager , RendezVousRepository $rdvrepo): Response
         {
              $searchValue = $request->request->get('searchValue');

               if ($searchValue) {
        // If there's a search value, perform a filtered search
              // If there's a search value, perform a filtered search
            /*$appointments = $entityManager->getRepository(RendezVous::class)-> findBySearchValue ($searchValue);*/
    /*  $appointments= $rdvrepo->findBySearchValue($searchValue);($searchValue);//
               } else {
        // If no search value, fetch all users
                $appointments = $entityManager->getRepository(RendezVous::class)->findAll();
              }*/

    // Render only the table content
    /*return $this->render('rendez_vous/index.html.twig', [
                'appointments' => $appointments,
         ]);
}*/
}
