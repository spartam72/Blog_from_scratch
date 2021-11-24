<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ArticleRepository $repo, UserRepository $userRepository): Response
    {


        // if ($request->query->get('user') && $request->query->get('user') == $this->getUser()->getFullName()) {
        //     $this->addFlash('success', 'Quel plaisir de vous revoir ' . $this->getUser()->getFullName());
        // }


        $lastArticles = $repo->recentArticles();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'title' => 'Accueil',
            'page_title' => 'Accueil',
            'myRoute' => $request->attributes->get('_route'),
            'lastArticles' => $lastArticles
        ]);
    }
}
