<?php

namespace App\Controller;

use App\Repository\CommentaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentModerationController extends AbstractController
{
    #[Route('/comment_moderation', name: 'app_comment_moderation')]
    public function index(CommentaryRepository $commentaryRepository): Response
    {
        // $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_MODERATOR']);

        $commentaryIsValidated = $commentaryRepository->findBy(
            [
                'isValidated' => false,
                'isArchived' => false,
            ],
            ['createdAt' => 'ASC'],
        );

        $commentaryIsArchived = $commentaryRepository->findBy(
            [
                'isValidated' => false,
                'isArchived' => true,
            ],
            ['createdAt' => 'ASC'],
        );

        return $this->render('comment_moderation/index.html.twig', [
            'commentaryIsValidated' => $commentaryIsValidated,
            'commentaryIsArchived' => $commentaryIsArchived,
        ]);
    }
}
