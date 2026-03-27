<?php

namespace App\Controller\admin;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use App\Security\RightVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\String\u;

final class RoleManagementController extends AbstractController
{
    #[Route('/role_management', name: 'app_role_management')]
    public function handleRequest(EntityManagerInterface $entityManager, Request $request, RoleRepository $roleRepository): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        if(!$this->isGranted(RightVoter::ROLE_CREATE) && !$this->isGranted(RightVoter::ROLE_EDIT) && !$this->isGranted(RightVoter::ROLE_DELETE)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        // On récupère tous les rôles de la liste
        $roles = $roleRepository->findAll();

        // On récupère les intentions depuis l'URL (?action=new ou ?editId=5)
        $editId = $request->query->get('editId');
        $deleteId = $request->query->get('deleteId');

        /** * Pourquoi initialiser à null ?
         * Si on n'est pas en mode édition, le bloc "if($editId)" est sauté.
         * Sans cette ligne, la variable n'existerait pas du tout pour le render() final,
         * ce qui provoquerait une erreur "Variable formEdit does not exist".
         */
        $formEdit = null;
        $formNew = null;

        // Si on parvient à récupèrer le paramètre deleteId
        if ($deleteId) {
            $this->denyAccessUnlessGranted(RightVoter::ROLE_DELETE);
            // Alors on vérifié si l'id est bien présent
            $roleToDelete = $roleRepository->find($deleteId);

            if ($roleToDelete && 'ROLE_USER' === $roleToDelete->getSymfonyRole()) {
                $this->addFlash('erreur', 'Il est impossible de supprimer ce rôle.');
            }

            // S'il est présent
            if ($roleToDelete && 'ROLE_USER' !== $roleToDelete->getSymfonyRole()) {
                // On va chercher tous les utilisateur possédant ce rôle
                $userWithThisRole = $roleToDelete->getUsers();

                // On parcours la liste de ces utilisateurs
                foreach ($userWithThisRole as $user) {
                    // On retire le rôle supprimé de l'utilisateur
                    $user->removeRole($roleToDelete);

                    // Vérification de sécurité : si l'utilisateur n'a plus de rôles,
                    // on s'assure qu'il récupère au moins le rôle de base.
                    // On va chercher l'entité Role qui correspond à 'ROLE_USER'
                    $defaultRole = $roleRepository->findOneBy(['symfonyRole' => 'ROLE_USER']);

                    // On lui assigne le rôle par défaut après avoir supprimé le sien
                    if ($defaultRole && !$user->getRolesEntities()->contains($defaultRole)) {
                        $user->addRole($defaultRole);
                    }
                }

                // On supprime le rôle
                try {
                    $entityManager->remove($roleToDelete);

                    $entityManager->flush();

                    $this->addFlash('succès', 'Le rôle a bien été supprimé.');
                } catch (\Exception $exception) {
                    $this->addFlash('erreur', 'Un problème est survenu. Veuillez réessayer.');
                }

                return $this->redirectToRoute('app_role_management');
            }
        }

        // Si la personne va choisir de créer un rôle
        if (!$editId && $this->isGranted(RightVoter::ROLE_CREATE)) {
            $newRole = new Role();
            $formNew = $this->createForm(RoleType::class, $newRole);
            $formNew->handleRequest($request);

            if ($formNew->isSubmitted()) {
                // 1. On récupère le nom directement dans le formulaire (évite le crash d'initialisation)
                $nomSaisi = $formNew->get('name')->getData();

                // 2. On vérifie s'il existe déjà
                if ($nomSaisi && $roleRepository->findRole($nomSaisi)) {
                    $this->addFlash('erreur', 'Ce rôle existe déjà.');
                // On ne met pas de "return" ici pour que la page se recharge
                // normalement avec le message d'erreur.
                }

                // Sinon on vérifie si le formulaire est valide
                elseif ($formNew->isValid()) {
                    try {
                        // On récupère le nom du rôle
                        $name = $newRole->getName(); // ex: "Modérateur" ou "Super Admin"

                        $symfonyRole = u($name)
                            ->ascii()            // Enlève les accents (é -> e)
                            ->snake()            // Remplace les espaces/majuscules par des underscores
                            ->upper()            // Tout en majuscules
                            ->prepend('ROLE_');  // Ajoute le préfixe

                        // On définit le code Symfony ex : "ROLE_MODERATEUR" ou "ROLE_SUPER_ADMIN"
                        $newRole->setSymfonyRole($symfonyRole->toString());

                        $entityManager->persist($newRole);
                        $entityManager->flush();

                        $this->addFlash('succès', 'Un nouveau rôle a été ajouté.');

                        return $this->redirectToRoute('app_role_management');
                    } catch (\Exception $exception) {
                        $this->addFlash('erreur', 'Un problème est survenu. Veuillez réessayer.');
                    }
                } else {
                    $this->addFlash('erreur', 'Le formulaire est invalide.');
                }
            }

        // Si la personne choisit de modifier un rôle
        } elseif ($editId) {
            $this->denyAccessUnlessGranted(RightVoter::ROLE_EDIT);
            // On vérifie que l'id de ce rôle existe déjà
            $role = $roleRepository->find($editId);

            if ($role && 'ROLE_USER' === $role->getSymfonyRole()) {
                $this->addFlash('erreur', 'Il est impossible de modifier ce rôle.');
            }

            if ($role && 'ROLE_USER' !== $role->getSymfonyRole()) {
                $formEdit = $this->createForm(RoleType::class, $role);
                $formEdit->handleRequest($request);

                if ($formEdit->isSubmitted()) {
                    if ($formEdit->isValid()) {
                        try {
                            $entityManager->flush();

                            $this->addFlash('succès', 'Le nom du rôle a bien été modifié.');

                            return $this->redirectToRoute('app_role_management', [
                                'id' => $role->getId(),
                            ]);
                        } catch (\Exception $exception) {
                            $this->addFlash('erreur', 'Un problème est survenu. Veuillez réessayer.');
                        }
                    } else {
                        $this->addFlash('erreur', 'Le formulaire est invalide.');
                    }
                }
            }
        }

        return $this->render('admin/role_management/index.html.twig', [
            'formNew' => $formNew ? $formNew->createView() : null,
            'formEdit' => $formEdit ? $formEdit->createView() : null,
            'editId' => $editId,
            'roles' => $roles,
        ]);
    }
}
