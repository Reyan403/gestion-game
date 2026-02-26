<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameValidationController extends AbstractController
{
    #[Route('/game/validation', name: 'app_game_validation')]
    public function index(): Response
    {
        return $this->render('game_validation/index.html.twig', [
            'controller_name' => 'GameValidationController',
        ]);
    }
}
