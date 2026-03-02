<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game')]
    public function index(Game $game, NoteRepository $noteRepository): Response
    {
        $note = $noteRepository->averageNoteForGame($game);

        return $this->render('game/index.html.twig', [
            'game' => $game,
            'average' => $note,
        ]);
    }
}
