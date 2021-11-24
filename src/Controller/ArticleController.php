<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// #[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_articles', methods: ['GET'])]
    public function index(ArticleRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {

        $allArticles = $repo->findBy([], ['createdAt' => 'DESC']);
        $per_page = 8;
        $totalPages = ceil(count($allArticles) /  $per_page);
        $nPage = !empty($request->query->get("page")) ? $request->query->get("page") : 1;


        // $lastArticles = $repo->findBy([], ['createdAt' => 'DESC'], 3);
        $lastArticles = $repo->recentArticles();

        $articles = $paginator->paginate(
            $allArticles, // on passe les données
            $request->query->getInt('page', 1), //récupération page en cours,sinon 1 par défaut
            $per_page //nombre d'articles par page
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'title' => 'Articles',
            'nPage' => $nPage,
            'totalPages' => $totalPages,
            'allArticles' => $allArticles,
            'myRoute' => $request->attributes->get('_route'),
            'lastArticles' => $lastArticles
        ]);
    }

    // ****** Affichage d'un article *********
    #[Route('/show/{id}', name: 'app_article_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Article $article, Request $request): Response
    {
        return $this->render('article/show.html.twig', [
            'title' => 'DETAILS ARTICLE',
            'page_title' => $article->getTitle(),
            'article' => $article,
            'myRoute' => $request->attributes->get('_route')
        ]);
    }

    // ****** Création d'article *********
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $repo, UserRepository $userRepo): Response
    {

        // $this->denyAccessUnlessGranted('ROLE_USER');

        // $lastArticles = $repo->findBy([], ['createdAt' => 'DESC'], 3);
        $lastArticles = $repo->recentArticles();

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvel article ajouté avec succès!');

            return $this->redirectToRoute('app_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'title' => 'New Article',
            'article' => $article,
            'form' => $form,
            'myRoute' => $request->attributes->get('_route'),
            'lastArticles' => $lastArticles
        ]);
    }


    // ****** Edition d'un article *********
    #[Route('/edit/{id}', name: 'app_article_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $repo): Response
    {

        // $this->denyAccessUnlessGranted('ROLE_USER');

        // $lastArticles = $repo->findBy([], ['createdAt' => 'DESC'], 3);
        $lastArticles = $repo->recentArticles();

        $title = $article->getTitle();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $title . ' mis à jour avec succès!');

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'title' => 'EDITER ARTICLE',
            'article' => $article,
            'form' => $form,
            'myRoute' => $request->attributes->get('_route'),
            'lastArticles' => $lastArticles
        ]);
    }


    // ****** Suppression d'un article *********
    #[Route('/delete/{id}', name: 'app_article_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {

            $this->addFlash('primary', $article->getTitle() . ' supprimé avec succès!');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articles', [], Response::HTTP_SEE_OTHER);
    }
}
