<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserManagementController extends AbstractController
{
    #[Route('/user_management', name: 'app_user_management')]
    public function index(): Response
    {
        return $this->render('admin/user_management/index.html.twig', [
            'controller_name' => 'UserManagementController',
        ]);
    }
}
