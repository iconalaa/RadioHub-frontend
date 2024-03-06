<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Donateur;
use App\Entity\Gratification;
use App\Form\Gratification1Type;
use App\Repository\DonateurRepository;
use App\Repository\GratificationRepository;
use Doctrine\ORM\EntityManager;
use Dompdf\Dompdf;
use Dompdf\Options;
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
    #[Route('/admin/', name: 'app_gratification_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator,Request $req, GratificationRepository $gratificationRepository): Response
    {
        $gratification = $gratificationRepository->findAll();
        $gratification = $paginator->paginate(
            $gratification, /* query NOT result */
            $req->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );
        #dd($gratification);
        return $this->render('gratification/index.html.twig', [
            'gratifications' => $gratification,
        ]);
    }

    #[Route('/admin/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, GratificationRepository $gratificationRepository): JsonResponse
    {
        $searchTerm = $request->query->get('search');
        $gratifications = $gratificationRepository->findBySearchTerm($searchTerm);

        $data = [];
        foreach ($gratifications as $gratification) {
            $data[] = [
                'id' => $gratification->getId(),
                'date' => $gratification->getDateGrat(),
                'title' => $gratification->getTitreGrat(),
                'Description' => $gratification->getDescGrat(),
                'Type' => $gratification->getTypeGrat(),
                //'Montant'=> $gratification->getMontant(),
                //'Donateur'=> $gratification->getIDDonateur(),
            ];
        }

        return new JsonResponse(['gratifications' => $data]);
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
            $donateur = $gratification->getIDDonateur();
            $donor = $entityManager->getRepository(Donateur::class)->find($donateur);



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
                'success_url' => $this->generateUrl('success_url', ['donorid' =>$donor ? $donor->getId() : null, 'gratificationId' => $gratification->getId(), ], UrlGeneratorInterface::ABSOLUTE_URL),
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
    public function successUrl(Request $req, EntityManagerInterface $entityManager): Response
    {
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/image/Cachet.png';

        // Check if the logo image exists
        if (file_exists($logoPath)) {
            // Get the logo image as base64 encoded string
            $logoData = base64_encode(file_get_contents($logoPath));
        } else {
            // Provide a fallback image or handle the case where the logo image is missing
            $logoData = '';
        }
        
        $donorId = $req->query->getInt('donorId');
        $gratificationId = $req->query->getInt('gratificationId');

        //$this->logger->debug('Donor ID: ' . $donorId);
        //$this->logger->debug('Gratification ID: ' . $gratificationId);
    
        $donor = $entityManager->getRepository(Donateur::class)->find($donorId);
        $gratification = $entityManager->getRepository(Gratification::class)->find($gratificationId);
    
        $html = $this->renderView('gratification/gratpdf.html.twig', [
            'donor' => $donor,
            'gratification' => $gratification,
            'logodata' => $logoData,
        ]);
    
        $options = new Options();
        $options->set('defaultFont', 'Arial','isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $pdfContent = $dompdf->output();
        
        // Create a response with PDF content
        $response = new Response($pdfContent);
        
        // Set the response headers
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="gratification_details.pdf"');
    
        return $response;
        /*
        
        return $this->render('gratification/success.html.twig', []);
*/
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
    


    #[Route('/admin/{id}', name: 'app_gratification_show', methods: ['GET'])]
    public function show(Gratification $gratification): Response
    {
        return $this->render('gratification/show.html.twig', [
            'gratification' => $gratification,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_gratification_edit', methods: ['GET', 'POST'])]
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

    #[Route('/admin/{id}', name: 'app_gratification_delete', methods: ['POST'])]
    public function delete(Request $request, Gratification $gratification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gratification->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gratification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gratification_index', [], Response::HTTP_SEE_OTHER);
    }


}
