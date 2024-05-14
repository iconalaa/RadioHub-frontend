<?php

namespace App\Controller;

use App\Entity\Droit;
use App\Form\DroitType;
use App\Repository\DroitRepository;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;

use Symfony\Component\Mercure\Update;

class DroitController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function test(DroitRepository $rep): Response
    {
        $droit = $rep->findBy(['image' => 3, 'role' => 'guest']);
        $radioloques = $rep->findRadioloqueWithoutDroit(3);
        $owner = $rep->findBy(['image' => 3, 'role' => 'owner'])[0]->getRadioloqist();

        return $this->render('droit/test.html.twig', [
            "droits" => $droit,
            "rads" => $radioloques,
            "owner" => $owner

        ]);
    }


    #[Route('/droit/{id}', name: 'app_droit')]
    public function index($id, Request $request, DroitRepository $rep, ImageRepository $repim, ManagerRegistry $em,  UserRepository $repr,HubInterface $hub): Response
    { // Get the list of IDs from the request query parameters

        $ids = $request->request->get('idrad');


        // $radioloqueWithoutDroit = $rep->findRadioloqueWithoutDroit($id);
        //$owner=$rep->findOwnerOfImage($id);

        //  if ($owner) {
        //    $radioloqueWithoutDroit = array_filter($radioloqueWithoutDroit, function ($radioloque) use ($owner) {
        //      return $radioloque->getId() !== $owner->getId();
        // });
        //}
        // Get users who have the role of "guest"
        //$guestUsers = $rep->findRadioloqueWithGuestRoleOnImage($id);


        // Process the provided IDs here if needed


        $droit = $rep->findBy(['image' => $id, 'role' => 'guest']);
        $radioloques = $rep->findRadioloqueWithoutDroit($id);

        $owner = $rep->findBy(['image' => $id, 'role' => 'owner'])[0]->getRadioloqist();
        if (!empty($ids)) {

            foreach ($ids as $rad) {

                $droit = new Droit();

                // Set the Radioloque ID and image ID
                $droit->setRadioloqist($repr->find($rad));
                $droit->setImage($repim->find($id));
                $droit->setRole("guest");
                $em->getManager()->persist($droit);
            }



                
        $sharedimage=$repim->find($id);
        $bodypart=$sharedimage->getBodypart();
        $aquisition=$sharedimage->getAquisationDate();
        $rad=$sharedimage->getRadiologist();

        $update = new Update(
            '/test',
            json_encode(['users' =>$ids,'idimage'=>$id,'aquisition'=>$aquisition,'bodypart'=>$bodypart
            ,'rad'=>$rad->getName()

            ])
        );

        $hub->publish($update);



            $em->getManager()->flush();
            // For now, let's just return a response indicating that IDs are provided
            return new Response('IDs provided: ' . implode(',', $ids));
        }

        $sharedimage=$repim->find($id);
        $bodypart=$sharedimage->getBodypart();
        $aquisition=$sharedimage->getAquisationDate();
        $rad=$sharedimage->getRadiologist();

        $update = new Update(
            '/test',
            json_encode(['users' =>$ids,'idimage'=>$id,'aquisition'=>$aquisition,'bodypart'=>$bodypart
            ,'rad'=>$rad->getName()

            ])
        );

        $hub->publish($update);


        return $this->render('droit/index.html.twig', [
            "droits" => $droit,
            "rads" => $radioloques,
            "owner" => $owner,
            "imageid" => $id

        ]);
    }
    #[Route('/droit/delete/{id}/{idimg}', name: 'deletedroit')]
    public function delete($id, $idimg, Request $request, ManagerRegistry $em, DroitRepository $rep,HubInterface $hub): Response
    {

        $droit = $rep->find($id);
        if ($droit and $droit->getRole() != 'owner') {

            $em->getManager()->remove($droit);

            // Flush the changes to the database
            $em->getManager()->flush();
                ///add delete real time event 
            $idrad=$droit->getRadioloqist()->getId();
    
            $update = new Update(
                '/delete',
                json_encode(['image'=>$idimg,'idrad'=>$idrad]));

            $hub->publish($update);


            // Return a JSON response indicating success
            return $this->json("200");
        } else {
            // If the Droit entity with the given ID doesn't exist, return a JSON response indicating failure
            return $this->json("401");
        }
    }



    #[Route('/your-route', name: 'your_route')]
    public function yourAction(Request $request, DroitRepository $rep)
    {

        $radiologists = $rep->findRadioloqueWithoutDroit(3);



        // Create a new instance of the form
        $form = $this->createForm(DroitType::class, null, [
            'radiologists' => $radiologists,
        ]);
        // Handle form submission if the request is POST
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Process form data if needed
            // For example, save data to the database
        }

        // Render the form template with the form view
        return $this->render('/droit/test1.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
