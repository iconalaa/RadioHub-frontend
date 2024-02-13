<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/medecin')]
class MedecinController extends AbstractController
{
    #[Route('/', name: 'app_medecin_index', methods: ['GET'])]
    public function index(MedecinRepository $medecinRepository): Response
    {
        return $this->render('medecin/index.html.twig', [
            'medecins' => $medecinRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_medecin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medecin = new Medecin();
        $form = $this->createForm(MedecinType::class, $medecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($medecin);
            $entityManager->flush();

            return $this->redirectToRoute('app_medecin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medecin/new.html.twig', [
            'medecin' => $medecin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medecin_show', methods: ['GET'])]
    public function show(Medecin $medecin): Response
    {
        return $this->render('medecin/show.html.twig', [
            'medecin' => $medecin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medecin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medecin $medecin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedecinType::class, $medecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_medecin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medecin/edit.html.twig', [
            'medecin' => $medecin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medecin_delete', methods: ['POST'])]
    public function delete(Request $request, Medecin $medecin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medecin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($medecin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medecin_index', [], Response::HTTP_SEE_OTHER);
    }
}
