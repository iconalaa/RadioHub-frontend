<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Article;
use App\Form\CommentType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\DoctorRepository;
use App\Repository\PatientRepository;
use App\Repository\RadiologistRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function frontOffice(Security $security): Response

    {
        $user = $security->getUser();



        return $this->render('home/home.html.twig', ['user'=>$user]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('home/profile.html.twig', []);
    }

    #[Route('/settings/{id}', name: 'app_settings')]
    public function settingsUser($id, UserPasswordHasherInterface $userPasswordHasher, UserRepository $user, ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger): Response
    {
        $em = $managerRegistry->getManager();
        $userEmpty = new User;
        $dataid = $user->find($id);
        $userEmpty->setName($dataid->getName());
        $userEmpty->setLastname($dataid->getLastname());
        $userEmpty->setEmail($dataid->getEmail());
        $userEmpty->setGender($dataid->getGender());
        $userEmpty->setDateBirth($dataid->getDateBirth());
        $originalEmail = $userEmpty->getEmail();
        $form = $this->createForm(UserType::class, $userEmpty);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

            $brochureFile = $form->get('brochureFilename')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $dataid->setBrochureFilename($newFilename);
            }
            $dataid->setPassword(
                $userPasswordHasher->hashPassword(
                    $dataid,
                    $form->get('password')->getData()
                )
            );

            $dataid->setName($userEmpty->getName());
            $dataid->setLastname($userEmpty->getLastname());
            $dataid->setDateBirth($userEmpty->getDateBirth());
            $dataid->setGender($userEmpty->getgender());
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_profile');
        }
        return $this->renderForm('home/settings.html.twig', [
            'f' => $form
        ]);
    }


    #[Route('/profile/delete/{id}', name: 'app_delete_user_profile')]
    public function deleteUserProfile($id, ManagerRegistry $managerRegistry, AuthorizationCheckerInterface $authChecker, UserRepository $user): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $user->find($id);

        

        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    // ! ------------------------- Hadil Friaa -------------------------------------

    #[Route('/blogSuivi/{id}', name: 'app_blog_suivi')]
    public function show_blog($id, ArticleRepository $ripo, Security $security, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $article = $ripo->findOneBy(['id' => $id]);
        $user = $security->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $currentDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $comment->setCreatedAt($currentDate);
            $comment->setIdUser($user);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_suivi', ['id' => $id]); // Redirection vers la même page pour rafraîchir les commentaires
        }

        // Récupérer les commentaires existants pour l'article
        $existingComments = $commentRepository->findBy(['article' => $article]);

        // Créer un formulaire de réponse pour chaque commentaire
        $replyForms = [];
        foreach ($existingComments as $existingComment) {
            // Créer un formulaire de réponse distinct pour chaque commentaire
            $replyComment = new Comment();
            $replyForm = $this->createForm(CommentType::class, $replyComment);
            $replyForms[$existingComment->getId()] = $replyForm->createView();
        }

        return $this->renderForm('article/blog_suivi.html.twig', [
            'comment' => $form,
            'articles' => $article,
            'existingComments' => $existingComments,
            'replyForms' => $replyForms,
            'user' =>$user,

        ]);
    }


    #[Route('/blog', name: 'app_blog')]
    public function showBlog(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request, SessionInterface $session)
    {
        $queryBuilder = $articleRepository->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC');
    
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $queryBuilder
                ->where('a.title LIKE :term')
                ->setParameter('term', '%' . $searchTerm . '%');
        }
    
        $query = $queryBuilder->getQuery();
    
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );
    
        // Get the favorites from session
        $favorites = $session->get('favorites', []);
    
        // Create an array to store likes for each article
        $articleLikes = [];
        foreach ($articles as $article) {
            $articleLikes[$article->getId()] = $article->getLikes();
        }
    
        return $this->render('article/blog.html.twig', [
            'articles' => $articles,
            'favorites' => $favorites,
            'articleLikes' => $articleLikes, // Pass article likes to the template
        ]);
    }
    #[Route('/toggle-favorite/{id}', name: 'toggle_favorite')]
    public function toggleFavorite($id, SessionInterface $session)
    {
        $articleId = (int) $id;
        $entityManager = $this->getDoctrine()->getManager();
        
        // Récupérer l'objet Article correspondant à l'identifiant
        $article = $entityManager->getRepository(Article::class)->find($articleId);
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }
        
        // Initialize the favorites array if not already present
        $favorites = $session->get('favorites', []);
        
        // Get the total likes for the specific article before toggling
        $totalLikes = $article->getLikes();
        
        // Check if the article is already in favorites
        $index = array_search($articleId, $favorites);
        
        // Toggle like for the specific article
        if ($index !== false) {
            unset($favorites[$index]);
            $article->setLikes(max(0, $totalLikes - 1)); // Decrease likes
        } else {
            $favorites[] = $articleId;
            $article->setLikes($totalLikes + 1); // Increase likes
        }
        
        // Update the favorites list in the session
        $session->set('favorites', $favorites);
        
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();
        
        // Rediriger vers la même page ou route après le basculement
        return $this->redirectToRoute('app_blog');
    }
}
