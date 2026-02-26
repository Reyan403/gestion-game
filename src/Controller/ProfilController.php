<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil/{id}/edit', name: 'profil_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if($form->isValid()) {
                try {
                    // On enregistre en base de données les changements.
                    $entityManager->flush();

                    $this->addFlash('success', 'Informations modifiées');

                    return $this->redirectToRoute('profil_edit', [
                        'id' => $user->getId(),
                    ]);
                } catch (\Exception $exception) {
                    $this->addFlash('error', 'Un problème est survenu. Veuillez réessayer.');
                }
            } else {
                $this->addFlash('error', 'Le formulaire est invalide');
            }
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
