<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil/{id}/edit', name: 'profil_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        // Si l'utilisateur n'est pas connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // // Si l'utilisateur cherche un autre id que le sien, dans la barre de recherche, pour modifier le profil par exemple
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                // get() est utilisé pour récupérer les données du formulaire
                $plainPassword   = $form->get('plainPassword')->getData();
                $newPassword     = $form->get('newPassword')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();

                // Je suis obligé de mettre cette variable : 
                // Si je mets pas ça, pour chaque erreur commise dans la modification du profil
                // Il y aura le message de succès et le message d'erreur
                $hasErrors = false;

                // On ne traite le mot de passe que si au moins un champ est rempli
                if ($plainPassword || $newPassword || $confirmPassword) {

                    if (!$plainPassword || !$newPassword || !$confirmPassword) {
                        $this->addFlash('error', 'Veuillez remplir tous les champs nécessaires pour un changement de mot de passe.');
                        $hasErrors = true;

                    // Si le mot de passe actuel est incorrect
                    } elseif (!$hasher->isPasswordValid($user, $plainPassword)) {
                        $this->addFlash('error', 'Mot de passe actuel incorrect.');
                        $hasErrors = true;

                    // Si la confirmation du nouveau mot de passe ne correspond pas
                    } elseif ($newPassword !== $confirmPassword) {
                        $this->addFlash('error', 'La confirmation du mot de passe ne correspond pas au nouveau mot de passe.');
                        $hasErrors = true;

                    // Tout est correct : on met à jour le mot de passe
                    } else {
                        $user->setPassword(
                            $hasher->hashPassword($user, $newPassword)
                        );
                    }
                }

                // On ne sauvegarde que si aucune erreur n'a été détectée
                if (!$hasErrors) {
                    try {
                        $entityManager->flush();
                        $this->addFlash('success', 'Profil mis à jour avec succès.');
                    } catch (\Exception $exception) {
                        $this->addFlash('error', 'Un problème est survenu. Veuillez réessayer.');
                    }
                }

                return $this->redirectToRoute('profil_edit', [
                    'id' => $user->getId(),
                ]);

            } else {
                $this->addFlash('error', 'Le formulaire est invalide.');
            }
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}

