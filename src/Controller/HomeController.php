<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, GameRepository $gameRepository, NoteRepository $noteRepository): Response
    {
        $category = $categoryRepository->findAll();
        $game = $gameRepository->findBy([
            'isValidated' => true,
            'isArchived' => false,
        ]);
        $note = $noteRepository->findAll();

        return $this->render('home/index.html.twig', [
            'category' => $category,
            'game' => $game,
            'note' => $note,
        ]);
    }
}
