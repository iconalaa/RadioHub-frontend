<?php

namespace App\Controller;

use App\Entity\Radiologist;
use App\Form\RadiologistType;
use App\Repository\RadiologistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/radiologist')]
class RadiologistController extends AbstractController
{
    #[Route('/', name: 'app_radiologist_index', methods: ['GET'])]
    public function index(RadiologistRepository $radiologistRepository): Response
    {
        return $this->render('radiologist/index.html.twig', [
            'radiologists' => $radiologistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_radiologist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $radiologist = new Radiologist();
        $form = $this->createForm(RadiologistType::class, $radiologist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($radiologist);
            $entityManager->flush();

            return $this->redirectToRoute('app_radiologist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('radiologist/new.html.twig', [
            'radiologist' => $radiologist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_radiologist_show', methods: ['GET'])]
    public function show(Radiologist $radiologist): Response
    {
        return $this->render('radiologist/show.html.twig', [
            'radiologist' => $radiologist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_radiologist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Radiologist $radiologist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RadiologistType::class, $radiologist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_radiologist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('radiologist/edit.html.twig', [
            'radiologist' => $radiologist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_radiologist_delete', methods: ['POST'])]
    public function delete(Request $request, Radiologist $radiologist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$radiologist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($radiologist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_radiologist_index', [], Response::HTTP_SEE_OTHER);
    }
}
