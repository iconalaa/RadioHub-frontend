<?php

namespace App\Controller;

use App\Form\UserType;

use App\Repository\ArticleRepository;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;



class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function frontOffice(): Response
    {
        return $this->render('home/home.html.twig', []);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('home/profile.html.twig', []);
    }

    #[Route('/settings/{id}', name: 'app_settings')]
    public function settingsUser($id, UserRepository $user, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $user->find($id);
        $form = $this->createForm(UserType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_profile');
        }
        return $this->renderForm('home/settings.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/blogSuivi/{id}', name: 'app_blog_suivi')]
    public function show_blog($id, ArticleRepository $ripo, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $article = $ripo->findOneBy(['id' => $id]);


        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $currentDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $comment->setCreatedAt($currentDate);

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

        ]);
    }


    #[Route('/blog', name: 'app_blog')]
    public function showBlog(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request, SessionInterface $session,)
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
            2
        );


        // Get the favorites from session
        $favorites = $session->get('favorites', []);

        // Get total likes
        $totalLikes = count($favorites);

        return $this->render('article/blog.html.twig', [
            'articles' => $articles,
            'favorites' => $favorites,
            'totalLikes' => $totalLikes, // Pass total likes to the template
        ]);
    }

    #[Route('/toggle-favorite/{id}', name: 'toggle_favorite')]
    public function toggleFavorite($id, SessionInterface $session)
    {
        $articleId = (int) $id;
        $favorites = $session->get('favorites', []);

        // Get the total likes before toggling
        $totalLikes = count($favorites);

        // Check if the article is already in favorites
        $index = array_search($articleId, $favorites);

        // Add or remove the article from the favorites list
        if ($index !== false) {
            unset($favorites[$index]);
        } else {
            $favorites[] = $articleId;
        }

        // Update the favorites list in the session
        $session->set('favorites', $favorites);

        // Return the total likes before toggling
        return $this->redirectToRoute('app_blog', ['totalLikes' => $totalLikes]);
    }
}
