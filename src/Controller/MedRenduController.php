<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Form\CompteRenduType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MedRenduController extends AbstractController
{
    #[Route('/med', name: 'app_med')]
    public function frontMed(Request $request): Response
    {
        // Créer une nouvelle instance de CompteRendu
        $compteRendu = new CompteRendu();

        // Créer le formulaire en utilisant CompteRenduType
        $form = $this->createForm(CompteRenduType::class, $compteRendu);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le compte rendu en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compteRendu);
            $entityManager->flush();

            // Rediriger vers une autre page ou afficher un message de succès
            // Ici, nous redirigeons simplement vers la même page pour l'exemple
            return $this->redirectToRoute('app_med');
        }

        // Rendre le modèle Twig avec le formulaire
        return $this->render('med/med.html.twig', [
            'form' => $form->createView(), // Passer le formulaire au modèle Twig
        ]);
    }
}
