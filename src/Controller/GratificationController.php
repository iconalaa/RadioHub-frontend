<?php

namespace App\Controller;

use App\Entity\Gratification;
use App\Form\Gratification1Type;
use App\Repository\GratificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Route('/gratification')]
class GratificationController extends AbstractController
{
    #[Route('/', name: 'app_gratification_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator,Request $req, GratificationRepository $gratificationRepository): Response
    {
        $gratification = $gratificationRepository->findAll();
        $gratification = $paginator->paginate(
            $gratification, /* query NOT result */
            $req->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
        #dd($gratification);
        return $this->render('gratification/index.html.twig', [
            'gratifications' => $gratification,
        ]);
    }

    #[Route('/new', name: 'app_gratification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$StripeSK): Response
    {
        Stripe::setApiKey($StripeSK);
        
        $gratification = new Gratification();
        $form = $this->createForm(Gratification1Type::class, $gratification,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gratification);
            $entityManager->flush();

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'unit_amount' => $gratification->getMontant() * 100, // Amount in cents
                            'product_data' => [
                                'name' => 'Gratification', // Product name
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url'  => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->redirect($session->url, 303);
           
            
        }
        return $this->render('gratification/new.html.twig', [
            'gratification' => $gratification,
            'form' => $form->createView(),
        ]);
    }

        #[Route('/success-url', name: 'success_url')]
        public function successUrl(): Response
        {
            return $this->render('gratification/success.html.twig', []);
        }
    
        #[Route('/cancel-url', name: 'cancel_url')]
        public function cancelUrl(): Response
        {
            return $this->render('gratification/cancel.html.twig', []);
        }
    
/*
            return $this->redirectToRoute('app_gratification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gratification/new.html.twig', [
            'gratification' => $gratification,
            'form' => $form,
        ]); */
    


    #[Route('/{id}', name: 'app_gratification_show', methods: ['GET'])]
    public function show(Gratification $gratification): Response
    {
        return $this->render('gratification/show.html.twig', [
            'gratification' => $gratification,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gratification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gratification $gratification, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Gratification1Type::class, $gratification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gratification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gratification/edit.html.twig', [
            'gratification' => $gratification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gratification_delete', methods: ['POST'])]
    public function delete(Request $request, Gratification $gratification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gratification->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gratification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gratification_index', [], Response::HTTP_SEE_OTHER);
    }


}
