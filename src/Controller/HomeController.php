<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response 
    {
        // Formulaire de connexion
        $loginError    = $authenticationUtils->getLastAuthenticationError();
        $lastUsername  = $authenticationUtils->getLastUsername();

        // Formulaire d'inscription
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            
            // Rôle attribué par défaut pour chaque compte inscrit 
            $roleRepository = $entityManager->getRepository(Role::class);
            $roleUser = $roleRepository->findOneBy(['name' => 'ROLE_USER']);

            if ($roleUser) {
                $user->addRole($roleUser);
            }


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'registrationForm' => $form,
            'error'            => $loginError,
            'last_username'    => $lastUsername,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        // Le firewall Symfony intercepte le POST vers cette URL.
        // En GET, on redirige vers la page d'accueil où se trouve le popup.
        return $this->redirectToRoute('app_home');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
