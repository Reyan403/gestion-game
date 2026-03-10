<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameManagementController extends AbstractController
{
    #[Route('/game_management', name: 'app_game_management')]
    public function index(): Response
    {
        return $this->render('admin/game_management/index.html.twig', [
            'controller_name' => 'GameManagementController',
        ]);
    }
}
