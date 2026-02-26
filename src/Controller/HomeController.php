<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Formulaire vide pour le rendu du popup d'inscription
        $registrationForm = $this->createForm(RegistrationFormType::class, new User());

        // Affichage de la page d'accueil avec les popups de connexion et d'inscription
        return $this->render('home/index.html.twig', [
            'registrationForm' => $registrationForm,
        ]);
    }
}
