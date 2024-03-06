<?php

namespace App\Controller;

use App\Form\UserType;

use App\Repository\ArticleRepository;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
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
public function show_blog($id, ArticleRepository $ripo, CommentRepository $commentRepository, Request $request, TranslatorInterface $translator,
EntityManagerInterface $entityManager )
{
    $article = $ripo->findOneBy(['id'=>$id]);


    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $comment->setArticle($article);
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
        'translations' => [
            'commentPlaceholder' => $translator->trans('Enter your comment'),
            'publishButton' => $translator->trans('Publish'),
            'translateButton' => $translator->trans('Translate'),
            // Ajoutez d'autres traductions ici au besoin
        ],
    ]);
}


#[Route('/blog', name: 'app_blog')]
public function showBlog(
    ArticleRepository $articleRepository,
    PaginatorInterface $paginator,
    Request $request,
    SessionInterface $session,
    EntityManagerInterface $entityManager

) {
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


    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog');
    }

    return $this->render('article/blog.html.twig', [
        'articles' => $articles,
        'favorites' => $session->get('favorites', []),
        'comment' => $form->createView(),

    ]);
}
#[Route('/toggle-favorite/{id}', name: 'toggle_favorite')]
public function toggleFavorite($id, SessionInterface $session)
{
    $articleId = (int) $id;
    $favorites = $session->get('favorites', []);

    // Vérifie si l'article est déjà dans les favoris
    $index = array_search($articleId, $favorites);

    // Ajoute ou supprime l'article de la liste des favoris
    if ($index !== false) {
        unset($favorites[$index]);
    } else {
        $favorites[] = $articleId;
    }

    // Met à jour la liste des favoris dans la session
    $session->set('favorites', $favorites);

    return $this->redirectToRoute('app_blog');
}

}
