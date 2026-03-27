<?php

namespace App\Controller\admin;

use App\Form\GameUpdateValidationType;
use App\Repository\GameUpdateRepository;
use App\Security\RightVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameUpdateValidationController extends AbstractController
{
    #[Route('/game_update_validation', name: 'app_game_update_validation')]
    public function index(GameUpdateRepository $gameUpdateRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        $this->denyAccessUnlessGranted(RightVoter::GAME_VALIDATE);
        
        $pendingGameUpdate = $gameUpdateRepository->findAll();

        // LES BOUTONS

        // Chercher l'id dans l'URL
        $id = $request->query->get('id');

        $form = null;

        if ($id) {
            $gameUpdateId = $gameUpdateRepository->find($id);

            if ($gameUpdateId) {
                // On récupère le Game associé via la relation dans GameUpdate
                $game = $gameUpdateId->getGame();

                $form = $this->createForm(GameUpdateValidationType::class, $gameUpdateId);
                $form->handleRequest($request);

                if ($form->isSubmitted()) {
                    if ($form->isValid()) {
                        /** @var SubmitButton $buttonApprove */
                        $buttonApprove = $form->get('approve');
                        /** @var SubmitButton $buttonRefuse */
                        $buttonRefuse = $form->get('refuse');

                        if ($buttonApprove->isClicked()) {
                            // On copie les données du GameUpdate vers le Game
                            $game->setTitle($gameUpdateId->getTitle());
                            $game->setDescription($gameUpdateId->getDescription());
                            $game->setImage($gameUpdateId->getImage());
                            $game->setPendingChange(false);

                            // On synchronise les catégories : on retire les anciennes et on ajoute celles du GameUpdate
                            foreach ($game->getCategories() as $category) {
                                $game->removeCategory($category);
                            }
                            foreach ($gameUpdateId->getCategories() as $category) {
                                $game->addCategory($category);
                            }

                            $entityManager->remove($gameUpdateId);

                            $this->addFlash('succès', 'La modification de ce jeu a été acceptée.');
                        } elseif ($buttonRefuse->isClicked()) {
                            $game->setPendingChange(false);
                            $entityManager->remove($gameUpdateId);
                            $this->addFlash('warning', 'La modification de ce jeu a été refusée.');
                        }

                        $entityManager->flush();

                        return $this->redirectToRoute('app_game_update_validation');
                    }
                }
            }
        }

        return $this->render('game_update_validation/index.html.twig', [
            'pendingGameUpdate' => $pendingGameUpdate,
        ]);
    }
}
