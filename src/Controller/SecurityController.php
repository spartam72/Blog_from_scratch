<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, ArticleRepository $repo): Response
    {
        if ($this->getUser()) {

            $this->addFlash('warning', 'Vous êtes déjà connecté(e).');
            return $this->redirectToRoute('app_home');
        }

        $lastArticles = $repo->recentArticles();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'myRoute' => 'app_login',
            'title' => 'Login',
            'lastArticles' => $lastArticles
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {

        // $this->addFlash('danger', 'Vous êtes déconnecté.');
        // return $this->redirectToRoute('app_home');

        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
