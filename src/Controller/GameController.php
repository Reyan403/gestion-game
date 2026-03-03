<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Game;
use App\Entity\Note;
use App\Form\CommentaryType;
use App\Repository\CommentaryRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game')]
    public function index(Game $game, NoteRepository $noteRepository, CommentaryRepository $commentaryRepository, Request $request, EntityManagerInterface $entityManager): Response {
        
        // GESTION DU VOTE VIA JAVASCRIPT 
        if ($request->isMethod('POST') && str_contains($request->headers->get('Content-Type'), 'application/json')) {
            $data = $request->toArray();
            $ratingValue = $data['rating'] ?? null;

            if ($ratingValue !== null) {
                
                // On vérifie que l'utilisateur est bien connecté
                $user = $this->getUser();
                if (!$user) {
                    return new JsonResponse(['error' => 'Vous devez être connecté pour voter.'], 403);
                }

                // On cherche si l'utilisateur a déjà noté ce jeu
                $note = $noteRepository->findOneBy([
                    'game' => $game, 
                    'user' => $user
                ]);

                // Si la note n'existe pas, on la crée
                if (!$note) {
                    $note = new Note();
                    $note->setGame($game);
                    $note->setUser($user);
                    $entityManager->persist($note); // On prépare la création
                }

                // On met à jour la valeur (grâce à ton setter !)
                $note->setNoteGame($ratingValue);

                // On envoie tout dans la base de données
                $entityManager->flush();

                return new JsonResponse([
                    'success' => true,
                    'message' => 'Vote enregistré avec succès !',
                ]);
            }

            return new JsonResponse(['error' => 'Note invalide'], 400);
        }

        // ------------------- LA MOYENNE DE TOUTES LES NOTES ------------------------------
        $average = $noteRepository->averageNoteForGame($game);

        // On cherche si l'utilisateur connecté a déjà une note 
        $userRating = null;
        if ($this->getUser()) {
            $existingNote = $noteRepository->findOneBy([
                'game' => $game, 
                'user' => $this->getUser()
            ]);
            
            if ($existingNote) {
                $userRating = $existingNote->getNoteGame(); // On récupère sa note (1 à 5)
            }
        }

        // -------------------------- FORMULAIRE POUR ENTRER UN COMMENTAIRE ----------------------
        $commentary = new Commentary();
        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if($form->isValid()) {
                try {

                    $commentary->setDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

                    // Permet de lier le commentaire au jeu actuel
                    $commentary->setGame($game);

                    // Permet de lier le commentaire à l'utilisateur seulement si l'utilisateur est connecté
                    if ($this->getUser()) {
                        $commentary->setUser($this->getUser());
                    } else {
                        return $this->redirectToRoute('app_login');
                    }

                    $entityManager->persist($commentary);

                    $entityManager->flush();

                    $this->addFlash('succès', 'Votre commentaire est en attente.');

                    return $this->redirectToRoute('app_game', [
                        'id' => $game->getId(),
                    ]);

                } catch (\Exception $exception) {
                    $this->addFlash('erreur', 'Un problème est survenu. Veuillez réessayer.');
                }
            } else {
                $this->addFlash('erreur', 'Le formulaire est invalide');
            }
        }

        // Permet de chercher les commentaires pour chaque jeu dans l'ordre décroissant 
        $commentaryList = $commentaryRepository->findBy(
            ['game' => $game],
            ['createdAt' => 'DESC']
        );

        return $this->render('game/index.html.twig', [
            'game' => $game,
            'commentaryList' => $commentaryList,
            'average' => $average,
            'form' => $form,
            'userRating' => $userRating, // On envoie la note à Twig
        ]);
    }
}