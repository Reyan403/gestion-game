<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameValidationController extends AbstractController
{
    #[Route('/game_validation', name: 'app_game_validation')]
    public function index(): Response
    {
        return $this->render('admin/game_validation/index.html.twig', [
            'controller_name' => 'GameValidationController',
        ]);
    }
}
