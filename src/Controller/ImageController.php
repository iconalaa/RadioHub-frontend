<?php

namespace App\Controller;

use App\Entity\Droit;
use App\Entity\Image;
use App\Entity\Radiologist;
use App\Form\EditType;
use App\Form\ImageType;
use App\Form\ImageTypeEdit;
use App\Repository\DroitRepository;
use App\Repository\ImageRepository;
use App\Repository\RadiologistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\User\User;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Security;

class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function index(ImageRepository $rep,Security $security,RadiologistRepository $reprad): Response
    { $user = $security->getUser();
        $rad=$reprad->findOneBy(['user'=> $user]);
        $images= $rep->findBy(['radiologist'=>$rad]);

        return $this->render('image/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/image/upload', name: 'image_upload')]
    public function new(Security $security,Request $request, SluggerInterface $slugger,ManagerRegistry $entityManager,ImageRepository $rep,RadiologistRepository $reprad): Response
    {
        $radiologist = $this->getUser(); // Assuming the logged-in user is the radiologist
        $rad=$reprad->findOneBy(['user'=>$radiologist]);






        $product = new Image();
        $form = $this->createForm(ImageType::class, $product);
        $droit=new Droit();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $rad=$reprad->findOneBy(['user'=> $user]);
            $product->setRadiologist($rad);
            $product->setFilename("changed");
            $entityManager->getManager()->persist($product);
            $entityManager->getManager()->flush();
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('filename')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {

                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $product->getId().'.dcm';

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('uploaded_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    dump($e->getMessage());
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setFilename($originalFilename.".dcm");
            }

            $entityManager->getManager()->persist($product);
            $entityManager->getManager()->flush();
            //Role owner
            $droit->setImage($product);
            $droit->setRole("owner");

            $user = $security->getUser();
            $rad=$reprad->findOneBy(['user'=> $user]);
            $droit->setRadioloqist($rad);

            $entityManager->getManager()->persist($droit);
            $entityManager->getManager()->flush();
            //////end role



            return $this->redirectToRoute('app_image');
        }

        return $this->renderForm('Image/add.html.twig', [
            'f' => $form,
        ]);





    }
    #[Route('/image/shared', name: 'shared')]

    public   function sharedImagesForRadiologist( Security $security ,RadiologistRepository $reprad): Response
    {$user = $security->getUser();
        $rad=$reprad->findOneBy(['user'=> $user]);
        $radiologistId=$rad->getId();
        // Récupérez le référentiel de l'entité Image
        $entityManager = $this->getDoctrine()->getManager();
        $imageRepository = $entityManager->getRepository(Image::class);

        // Requête DQL pour récupérer les images partagées pour un radiologue spécifique
        $query = $entityManager->createQuery(
            'SELECT i
            FROM App\Entity\Image i
            JOIN App\Entity\Droit d
            WHERE i.id = d.image
            AND d.role = :role
            AND d.radioloqist = :radiologistId'
        )->setParameter('role', 'guest')
            ->setParameter('radiologistId', $radiologistId);

        // Exécutez la requête et récupérez les résultats
        $sharedImages = $query->getResult();

        // Affichez ou traitez les images partagées comme vous le souhaitez
        // Par exemple, passez-les à un modèle pour les afficher
        return $this->render('Image/share.html.twig', [
            'images' => $sharedImages,
        ]);
    }








    #[Route('/image/delete/{id}', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(Request $request,ImageRepository $rep,$id,ManagerRegistry $man)
    {
// Get the entity manager
        $entityManager = $man->getManager();

        // Find the image by ID
        $image = $entityManager->getRepository(Image::class)->find($id);

        if (!$image) {
            throw $this->createNotFoundException('Image not found');
        }

        // Mark the image as deleted by removing it from the entity manager
        $entityManager->remove($image);

        // Flush changes to the database
        $entityManager->flush();
        return $this->redirectToRoute('app_image');


    }









    #[Route('/image/edit/{id}', name: 'edit_image', methods: ['GET', 'POST'])]
    public function edit(Request $request, SluggerInterface $slugger, ImageRepository $rep, $id)
    {
        $image = $rep->find($id);

        // Create the edit form
        $form = $this->createForm(EditType::class, $image);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            // Check if the form is valid
            if ($form->isValid()) {
                // Form is valid, proceed with processing form data
                /** @var UploadedFile $brochureFile */
                $brochureFile = $form->get('filename')->getData();

                // Handle file upload and other processing logic here...

                // Persist the changes to the database
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                // Redirect to a page indicating successful update
                return $this->redirectToRoute('app_image', ['id' => $image->getId()]);
            } else {
                // Form is not valid, handle validation errors
                // You can optionally add code here to log or handle validation errors
            }
        }

        // Render the form for editing the image
        return $this->renderForm('image/edit.html.twig', [
            'f' => $form
        ]);
    }



    #[Route('/image/consult/{id}', name: 'consult', methods: ['GET', 'POST'])]

    public function consult($id ,Security $security,RadiologistRepository $reprad,DroitRepository $repd)
    {
        $user = $security->getUser();
        $rad=$reprad->findOneBy(['user'=> $user]);
        $radiologistId=$rad->getId();



       $droit= $repd->findOneBy(['image' => $id, 'radioloqist' => $rad]);
       $droit=$droit->getRole();



        return $this->render('image/consult.html.twig',['id'=>$id,"droit"=>$droit]);
    }








}
