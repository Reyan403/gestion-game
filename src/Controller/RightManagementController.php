<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RightManagementController extends AbstractController
{
    #[Route('/right/management', name: 'app_right_management')]
    public function index(): Response
    {
        return $this->render('right_management/index.html.twig', [
            'controller_name' => 'RightManagementController',
        ]);
    }
}
