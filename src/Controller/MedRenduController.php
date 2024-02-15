<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Form\CompteRenduType;
use App\Form\CompteRenduType1;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;



class MedRenduController extends AbstractController
{
    #[Route('/add-interprétation/{id}', name: 'add_interprétation')]
    public function addInterpretation(Request $request, int $id): Response
    {
        // Récupérer le compte-rendu à partir de l'ID
        $entityManager = $this->getDoctrine()->getManager();
        $compteRendu = $entityManager->getRepository(CompteRendu::class)->find($id);
    
        // Créer le formulaire pour la mise à jour du compte-rendu
        $form = $this->createForm(CompteRenduType1::class, $compteRendu);
        $form->handleRequest($request);
    
        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les changements dans la base de données
            $entityManager->flush();
    
            // Rediriger vers une autre page après la mise à jour
            return $this->redirectToRoute('app_med');
        }
    
        // Afficher le formulaire de mise à jour
        return $this->render('med/update_compte_rendu.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/med', name: 'app_med')]
    public function frontMed(Request $request): Response
    {
        $entityManager = $this->managerRegistry->getManager();

        $id=1;
        
        $compteRendus = $entityManager->getRepository(CompteRendu::class)->findBy(['id_medecin' => $id]);

        return $this->render('med/med.html.twig', [
            'compteRendus' => $compteRendus,
        ]);
    }

    


    


}
