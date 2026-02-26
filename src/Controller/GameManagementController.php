<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameManagementController extends AbstractController
{
    #[Route('/game/management', name: 'app_game_management')]
    public function index(): Response
    {
        return $this->render('game_management/index.html.twig', [
            'controller_name' => 'GameManagementController',
        ]);
    }
}
