<?php

namespace App\Controller\admin;

use App\Controller\Base\BaseController;
use App\Form\CommentModerationType;
use App\Repository\CommentaryRepository;
use App\Security\RightVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentModerationController extends BaseController
{
    #[Route('/comment_moderation', name: 'app_comment_moderation')]
    public function index(CommentaryRepository $commentaryRepository, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        
        // Vérifie si l'utilisateur a le droit de modérer les commentaires
        $this->denyAccessUnlessGranted(RightVoter::COMMENT_VALIDATE);

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

        $commentaryIsValidatedPaginate = $paginator->paginate(
            $commentaryIsValidated, 
            $request->query->getInt('page', 1),
            10
        );

        $commentaryIsArchivedPaginate = $paginator->paginate(
            $commentaryIsArchived, 
            $request->query->getInt('page', 1),
            2
        );

        // LES BOUTONS
        // Cette ligne génère un URL en récupérant l'id du commentaire. 
        $id = $request->query->get('id');

        $form = null;

        if ($id) {
            $commentary = $commentaryRepository->find($id);

            if ($commentary) {

                $form = $this->createForm(CommentModerationType::class, $commentary);
                $form->handleRequest($request);

                if ($form->isSubmitted()) {
                    if ($form->isValid()) {

                        // C'est pour faire comprendre à mon VSC que c'est un bouton
                        // Sinon il m'indique que isClicked() est une erreur car cette méthode est spécifique que pour les boutons
                        /** @var SubmitButton $buttonApprove */
                        $buttonApprove = $form->get('approve');
                        /** @var SubmitButton $buttonArchive */
                        $buttonArchive = $form->get('archive');
                        /** @var SubmitButton $buttonRemove */
                        $buttonRemove = $form->get('remove');
                        /** @var SubmitButton $buttonRestore */
                        $buttonRestore = $form->get('restore');

                        if ($buttonApprove->isClicked()) {
                            $commentary->setIsValidated(true);
                            $commentary->setIsArchived(false);
                            $this->addFlash('succès', 'Le commentaire a été approuvé.');
                        } else if ($buttonArchive->isClicked()) {
                            $commentary->setIsValidated(false);
                            $commentary->setIsArchived(true);
                            $this->addFlash('warning', 'Le commentaire a été archivé.');
                        } else if ($buttonRestore->isClicked()) {
                            $commentary->setIsValidated(false);
                            $commentary->setIsArchived(false);
                            $this->addFlash('succès', 'Le commentaire a été restauré.');
                        } else if ($buttonRemove->isClicked()) {
                            $entityManager->remove($commentary);
                            $this->addFlash('succès', 'Le commentaire a bien été supprimé.');
                        }

                        $entityManager->flush();

                        return $this->redirectToRoute('app_comment_moderation');
                    }
                }
            }
        }

        return $this->render('admin/comment_moderation/index.html.twig', [
            'commentaryIsValidated' => $commentaryIsValidatedPaginate,
            'commentaryIsArchived' => $commentaryIsArchivedPaginate,
            'form' => $form,
        ]);
    }
}
