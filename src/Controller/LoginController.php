<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        // Le popup de login est dans l'accueil — on redirige toujours vers app_home
        // En cas d'échec, Symfony stocke l'erreur en session (failure_path: app_home dans security.yaml)
        return $this->redirectToRoute('app_home');
    }

    // Appelée via {{ render(controller('App\\Controller\\LoginController::loginPopup')) }}
    // Cette fonction permet d'afficher les erreurs et le dernier nom utiliser par l'utilisateur
    // On ne le met dans la fonction login() car sinon pour chaque erreur du formulaire, ça nous renvois dans sur la page d'accueil au lieu de nous laisser sur le popup
    public function loginPopup(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('login/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
