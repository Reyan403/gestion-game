<?php

namespace App\Controller\admin;

use App\Form\UserManagementType;
use App\Repository\CommentaryRepository;
use App\Repository\GameRepository;
use App\Repository\GameUpdateRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserManagementController extends AbstractController
{
    #[Route('/user_management', name: 'app_user_management')]
    public function index(UserRepository $userRepository, CommentaryRepository $commentaryRepository, NoteRepository $noteRepository,
     GameRepository $gameRepository, GameUpdateRepository $gameUpdateRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        $formEdit = null;

        $deleteId = $request->query->get('deleteId');
        $editId = $request->query->get('editId');

         if ($editId) {
            $userId = $userRepository->find($editId);

            $formEdit = $this->createForm(UserManagementType::class, $userId);
            $formEdit->handleRequest($request);

            if($formEdit->isSubmitted()) {
                if($formEdit->isValid()) {
                    try {

                        $entityManager->flush();
                        $this->addFlash('succès', 'L\'utilisateur a bien été modifié.');

                        return $this->redirectToRoute('app_user_management', [
                            'id' => $userId->getId(),
                        ]);

                    } catch (\Exception $exception) {
                        $this->addFlash('erreur', 'Un problème est survenu. Veuillez réessayer.');
                    }
                } else {
                    $this->addFlash('erreur', 'Le formulaire est invalide.');
                } 
            }
        }

        if($deleteId) {
            $userToDelete = $userRepository->find($deleteId);

            try {
                // Cela va permettre que, lorsqu'on supprime un utilisateur, ça va attribuer les colonnes "user_id" en null
                // Pour conserver l'historique, s'ils ont ajouter ou modifier un jeu ou pas
                $userGames = $gameRepository->findBy(['user' => $userToDelete]);
                $userValidatedGames = $gameRepository->findBy(['isValidatedBy' => $userToDelete]);
                $userGameUpdates = $gameUpdateRepository->findBy(['user' => $userToDelete]);

                foreach ($userGames as $game) {
                    $game->setUser(null);
                }

                foreach ($userValidatedGames as $game) {
                    $game->setIsValidatedBy(null);
                }

                foreach ($userGameUpdates as $gameUpdate) {
                    $gameUpdate->setUser(null);
                }
                
                $entityManager->remove($userToDelete);
                $entityManager->flush();

                $this->addFlash('succès', 'L\'utilisateur a bien été supprimé.');
            } catch (\Exception $exception) {
                $this->addFlash('erreur', 'Une erreur est survenu. Veuillez réessayer.');
            }

            return $this->redirectToRoute('app_user_management');
        }

        return $this->render('admin/user_management/index.html.twig', [
            'users' => $users,
            // Quand tu es sur la page "Modifier", il envoie le formulaire d'édition et null pour l'ajout.
            'formEdit' => $formEdit ? $formEdit->createView() : null,
            'editId' => $editId,
        ]);
    }
}
