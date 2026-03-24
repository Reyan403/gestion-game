<?php

namespace App\Controller\admin;

use App\Form\GameUpdateValidationType;
use App\Repository\GameUpdateRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameUpdateValidationController extends AbstractController
{
    #[Route('/game_update_validation', name: 'app_game_update_validation')]
    public function index(GameUpdateRepository $gameUpdateRepository, StatusRepository $statusRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $pendingGameUpdate = $gameUpdateRepository->findPendingGameUpdate();

        // LES BOUTONS

        // Chercher l'id dans l'URL
        $id = $request->query->get('id');

        $form = null;

        if ($id) {
            $gameUpdateId = $gameUpdateRepository->find($id);

            if ($gameUpdateId) {
                $form = $this->createForm(GameUpdateValidationType::class, $gameUpdateId);
                $form->handleRequest($request);

                if ($form->isSubmitted()) {
                    if ($form->isValid()) {
                        /** @var SubmitButton $buttonApprove */
                        $buttonApprove = $form->get('approve');
                        /** @var SubmitButton $buttonRefuse */
                        $buttonRefuse = $form->get('refuse');

                        if ($buttonApprove->isClicked()) {
                            $statusApprove = $statusRepository->findOneBy(['name' => 'accepted']);
                            $gameUpdateId->setStatus($statusApprove);

                            $game = $gameUpdateId->getGame();

                            $game->setTitle($gameUpdateId->getTitle());
                            $game->setDescription($gameUpdateId->getDescription());
                            $game->setImage($gameUpdateId->getImage());

                            foreach ($gameUpdateId->getCategories() as $category) {
                                $game->addCategory($category);
                            }

                            $this->addFlash('succès', 'La modification de ce jeu a été accepté.');
                        } elseif ($buttonRefuse->isClicked()) {
                            $statusRefuse = $statusRepository->findOneBy(['name' => 'refused']);
                            $gameUpdateId->setStatus($statusRefuse);
                            $this->addFlash('succès', 'La modification de ce jeu a été refusé.');
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
