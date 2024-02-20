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

#[Route('/gratification')]
class GratificationController extends AbstractController
{
    #[Route('/', name: 'app_gratification_index', methods: ['GET'])]
    public function index(GratificationRepository $gratificationRepository): Response
    {
        return $this->render('gratification/index.html.twig', [
            'gratifications' => $gratificationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gratification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gratification = new Gratification();
        $form = $this->createForm(Gratification1Type::class, $gratification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gratification);
            $entityManager->flush();

            return $this->redirectToRoute('app_gratification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gratification/new.html.twig', [
            'gratification' => $gratification,
            'form' => $form,
        ]);
    }

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
