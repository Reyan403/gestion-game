<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RightManagementController extends AbstractController
{
    #[Route('/right_management', name: 'app_right_management')]
    public function index(): Response
    {
        return $this->render('admin/right_management/index.html.twig', [
            'controller_name' => 'RightManagementController',
        ]);
    }
}
