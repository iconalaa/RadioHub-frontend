<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\RendezVous;
use App\Form\RendezVousType1;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function new(Request $request, ManagerRegistry $entityManager, MailService $mailService,SalleRepository $salle): Response
    {
        $user = $this->getUser(); // Assuming you have a method to get the logged-in user

        $rendezVou = new RendezVous();
        $rendezVou->setUser($user);
        $salles =$salle->findAll();
    
        $rendezVou = new RendezVous();
        $form = $this->createForm(RendezVousType1::class, $rendezVou );
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVou->setUser($user); // Explicitly set the user association (optional)
    
            $entityManager->getManager()->persist($rendezVou);
            $entityManager->getManager()->flush();
    
            // Envoi de l'e-mail ici après avoir persisté la nouvelle proposition
            $mailService->sendEmail();
    
            return $this->redirectToRoute('app_booked_appointment');
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

   
}
