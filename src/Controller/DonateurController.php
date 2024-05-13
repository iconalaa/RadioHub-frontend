<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Donateur;
use App\Form\Donateur1Type;
use App\Repository\DonateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/donateur')]
class DonateurController extends AbstractController
{
    #[Route('/admin/', name: 'app_donateur_index', methods: ['GET'])]
    public function index(DonateurRepository $donateurRepository,PaginatorInterface $paginator,Request $req): Response
    {
        $donateurs = $donateurRepository->findAll();
        $donateurs = $paginator->paginate(
            $donateurs, /* query NOT result */
            $req->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('donateur/index.html.twig', [
            'donateurs' => $donateurs,
        ]);
    }

    #[Route('/new', name: 'app_donateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,DonateurRepository $drepo): Response
    {
        $donateur = new Donateur();
        $form = $this->createForm(Donateur1Type::class, $donateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donateur);
            $entityManager->flush();

            $telephone = "+216" . $donateur->getTelephone();
            $drepo->sms(strval($telephone));
            return $this->redirectToRoute('app_gratification_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donateur/new.html.twig', [
            'donateur' => $donateur,
            'form' => $form,
        ]);
    }

    #[Route('/admin/search', name: 'app_donateur_search', methods: ['GET'])]
    public function search(Request $request, DonateurRepository $donateurRepository): JsonResponse
    {
        $searchTerm = $request->query->get('search');
        $donateurs = $donateurRepository->findBySearchTerm($searchTerm);

        $data = [];
        foreach ($donateurs as $donateur) {
            $data[] = [
                'id' => $donateur->getId(),
                'nom' => $donateur->getNomDonateur(),
                'prenom' => $donateur->getPrenomDonateur(),
                'type' => $donateur->getTypeDonateur(),
                'email' => $donateur->getEmail(),
                'telephone'=> $donateur->getTelephone(),
            ];
        }

        return new JsonResponse(['donateurs' => $data]);
    }

    #[Route('/admin/{id}', name: 'app_donateur_show', methods: ['GET'])]
    public function show(Donateur $donateur): Response
    {
        return $this->render('donateur/show.html.twig', [
            'donateur' => $donateur,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_donateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Donateur $donateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Donateur1Type::class, $donateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donateur/edit.html.twig', [
            'donateur' => $donateur,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_donateur_delete', methods: ['POST'])]
    public function delete(Request $request, Donateur $donateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
