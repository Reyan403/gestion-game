<?php

namespace App\Controller\admin;

use App\Controller\Base\BaseController;
use App\Form\RightTableType;
use App\Repository\RightRepository;
use App\Repository\RoleRepository;
use App\Security\RightVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RightManagementController extends BaseController
{
    #[Route('/right_management', name: 'app_right_management')]
    public function index(RightRepository $rightRepository, RoleRepository $roleRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->requireLogin()) return $redirect;

        if(!$this->isGranted(RightVoter::ROLE_CREATE) && !$this->isGranted(RightVoter::ROLE_EDIT) && !$this->isGranted(RightVoter::ROLE_DELETE) && !$this->isGranted(RightVoter::ROLE_ASSIGN)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        $rights = $rightRepository->findAll();
        $roles = $roleRepository->findAll();

        $form = $this->createForm(RightTableType::class, ['rights' => $rights]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    // Parcourir les droits et les rôles
                    foreach ($rights as $right) {
                        foreach ($roles as $role) {
                            
                            // On vérifie l'état du Droit : "Est-ce que ce Droit est rattaché à ce Rôle ?"
                            $hasRight = $right->getRoles()->contains($role);
                            
                            // On vérifie l'état du Rôle : "Est-ce que ce Rôle sait qu'il possède ce Droit ?"
                            $roleHasRight = $role->getRights()->contains($right);

                            // Cas de l'ajout : La case a été cochée. Le Droit a reçu le Rôle, 
                            // mais le Rôle n'est pas encore au courant dans la mémoire de Symfony.
                            if ($hasRight && !$roleHasRight) {
                                // On met à jour le Rôle pour créer le lien dans les deux sens.
                                $role->addRight($right); 
                            } 
                            
                            // Cas de la suppression : La case a été décochée. Le Droit n'a plus ce Rôle,
                            // mais le Rôle croit encore qu'il possède ce Droit.
                            elseif (!$hasRight && $roleHasRight) {
                                // On retire le Droit du Rôle pour casser le lien proprement.
                                $role->removeRight($right); 
                            }
                        }
                    }

                    $entityManager->flush();
                    $this->addFlash('succès', 'Permissions mises à jour avec succès.');
                    return $this->redirectToRoute('app_right_management');

                } catch (\Exception $exception) {
                    $this->addFlash('erreur', 'Une erreur est survenue. Veuillez réessayer.');
                }
            } 
        }

        return $this->render('admin/right_management/index.html.twig', [
            'form' => $form->createView(),
            'role' => $roles,
        ]);
    }
}
