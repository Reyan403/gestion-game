<?php

namespace App\Controller\admin;

use App\Form\GameValidationType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameValidationController extends AbstractController
{
    #[Route('/game_validation', name: 'app_game_validation')]
    public function index(GameRepository $gameRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
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
                        // C'est pour faire comprendre à mon VSC que c'est un bouton
                        // Sinon il m'indique que isClicked() est une erreur car cette méthode est spécifique que pour les boutons
                        /** @var SubmitButton $buttonApprove */
                        $buttonApprove = $form->get('approve');
                        /** @var SubmitButton $buttonArchive */
                        $buttonArchive = $form->get('archive');
                        /** @var SubmitButton $buttonRemove */
                        $buttonRemove = $form->get('remove');
                        /** @var SubmitButton $buttonRestore */
                        $buttonRestore = $form->get('restore');

                        if ($buttonApprove->isClicked()) {
                            $gameId->setIsValidated(true);
                            $gameId->setIsArchived(false);
                            $gameId->setIsValidatedBy($this->getUser());
                            $gameId->setWhenIsValidated(new \DateTime('now'));
                            $this->addFlash('succès', 'Le jeu a été approuvé.');
                        } elseif ($buttonArchive->isClicked()) {
                            $gameId->setIsValidated(false);
                            $gameId->setIsArchived(true);
                            $this->addFlash('warning', 'Le jeu a été archivé.');
                        } elseif ($buttonRestore->isClicked()) {
                            $gameId->setIsValidated(false);
                            $gameId->setIsArchived(false);
                            $this->addFlash('succès', 'Le jeu a été restauré.');
                        } elseif ($buttonRemove->isClicked()) {
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
            'pendingGame' => $pendingGame,
            'archivedGame' => $archivedGame,
            'approvedGame' => $approvedGame,
        ]);
    }
}
