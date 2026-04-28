<?php

namespace App\Controller\admin;

use App\Controller\Base\BaseController;
use App\Form\GameValidationType;
use App\Repository\GameRepository;
use App\Security\RightVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameValidationController extends BaseController
{
    #[Route('/game_validation', name: 'app_game_validation')]
    public function index(GameRepository $gameRepository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        
        $this->denyAccessUnlessGranted(RightVoter::GAME_VALIDATE);
        
        // Afficher les jeux en attente
        $pendingGame = $gameRepository->findby([
            'isValidated' => false,
            'isArchived' => false,
        ]);

        // Afficher les jeux archivés
        $archivedGame = $gameRepository->findBy([
            'isValidated' => false,
            'isArchived' => true,
        ]);

        // Afficher les jeux approuvés
        $approvedGame = $gameRepository->findBy([
            'isValidated' => true,
            'isArchived' => false,
        ],
            [
                'whenIsValidated' => 'DESC',
            ]);

         
        $pendingGamePaginate = $paginator->paginate(
            $pendingGame, 
            $request->query->getInt('page', 1),
            10
        );

        $archivedGamePaginate = $paginator->paginate(
            $archivedGame, 
            $request->query->getInt('page', 1),
            10
        );

        $approvedGamePaginate = $paginator->paginate(
            $approvedGame, 
            $request->query->getInt('page', 1),
            10
        );

        // BOUTONS
        $id = $request->query->get('id');

        $form = null;

        if ($id) {
            $gameId = $gameRepository->find($id);

            if ($gameId) {
                $form = $this->createForm(GameValidationType::class, $gameId);
                $form->handleRequest($request);

                if ($form->isSubmitted()) {
                    if ($form->isValid()) {
                        $clicked = $this->getClickedButton($form, ['approve', 'archive', 'restore', 'remove']);

                        if ($clicked === 'approve') {
                            $gameId->setIsValidated(true);
                            $gameId->setIsArchived(false);
                            $gameId->setIsValidatedBy($this->getUser());
                            $gameId->setWhenIsValidated(new \DateTime('now'));
                            $this->addFlash('succès', 'Le jeu a été approuvé.');
                        } elseif ($clicked === 'archive') {
                            $gameId->setIsValidated(false);
                            $gameId->setIsArchived(true);
                            $this->addFlash('warning', 'Le jeu a été archivé.');
                        } elseif ($clicked === 'restore') {
                            $gameId->setIsValidated(false);
                            $gameId->setIsArchived(false);
                            $this->addFlash('succès', 'Le jeu a été restauré.');
                        } elseif ($clicked === 'remove') {
                            $entityManager->remove($gameId);
                            $this->addFlash('succès', 'Le jeu a bien été supprimé.');
                        }

                        $entityManager->flush();

                        return $this->redirectToRoute('app_game_validation');
                    }
                }
            }
        }

        return $this->render('admin/game_validation/index.html.twig', [
            'pendingGame' => $pendingGamePaginate,
            'archivedGame' => $archivedGamePaginate,
            'approvedGame' => $approvedGamePaginate,
        ]);
    }
}
