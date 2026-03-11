<?php

namespace App\Controller\admin;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameManagementController extends AbstractController
{
    #[Route('/game_management', name: 'app_game_management')]
    public function index(GameRepository $gameRepository, EntityManagerInterface $entityManager, Request $request, #[Autowire('%image_dir%')] $imageDir): Response
    {
        $game = $gameRepository->findAll();

        $editId = $request->query->get('editId');
        $deleteId = $request->query->get('deleteId');

        $formEdit = null;
        $formNew = null;

        if(!$editId) {
            $newGame = new Game();
            $formNew = $this->createForm(GameType::class, $newGame);
            $formNew->handleRequest($request);

            if($formNew->isSubmitted()) {
                if($formNew->isValid()) {

                    // On vérifie si un nouveau fichier a été envoyé via le champ 'image' du formulaire
                    if ($image = $formNew['image']->getData()) {

                        // On génère un nom de fichier unique (ex: 65f4a1b2c3.jpg) pour éviter les doublons sur le serveur
                        $fileName = uniqid() . '.' . $image->guessExtension();

                        // On déplace physiquement le fichier du dossier temporaire vers ton dossier de destination (public/...)
                        $image->move($imageDir, $fileName);

                        // On met à jour le nom du fichier dans l'objet Game en y ajoutant le préfixe 'img/' pour la base de données
                        $newGame->setImage('img/' . $fileName);
                    }

                    try {

                        $entityManager->persist($newGame);
                        $entityManager->flush();
                        $this->addFlash('succès', 'Le jeu a été ajouté.');

                        return $this->redirectToRoute('app_game_management');

                    } catch (\Exception $exception) {
                        $this->addFlash('erreur', 'Une erreur est survenue. Veuillez réessayer.');
                    }
                } else {
                    $this->addFlash('erreur', 'Le formulaire est invalide.');
                }
            }
        } else if ($editId) {
            // On vérifie que l'id de ce jeu existe déjà
            $editGame = $gameRepository->find($editId);

            $formEdit = $this->createForm(GameType::class, $editGame);
            $formEdit->handleRequest($request);

            if($formEdit->isSubmitted()) {
                if($formEdit->isValid()) {

                    if($image = $formEdit['image']->getData()) {
                        $fileName = uniqid().'.'.$image->guessExtension();
                        $image->move($imageDir, $fileName);

                        $editGame->setImage('img/' . $fileName);
                    }

                    try {

                        $entityManager->flush();
                        $this->addFlash('succès', 'Le jeu a été modifié.');

                        return $this->redirectToRoute('app_game_management', [
                            'id' => $editGame->getId(),
                        ]);

                    } catch (\Exception $exception) {
                        $this->addFlash('erreur', 'Une erreur est survenue. Veuillez réessayer.');
                    } 
                } else {
                    $this->addFlash('erreur', 'Le formulaire est invalide.');
                }
            }
        }

        if($deleteId) {
            $gameToDelete = $gameRepository->find($deleteId);

            try {

                $imagePath = $gameToDelete->getImage();
                    
                if ($imagePath) {
                    // On utilise basename() pour récupérer juste "mon-image.jpg" sans le "img/"
                    $fileName = basename($imagePath);
                    
                    // On construit le chemin absolu vers le fichier sur le disque
                    $fileAbsolute = $imageDir . '/' . $fileName;

                    // On vérifie que le fichier existe bien avant de le supprimer
                    if (file_exists($fileAbsolute) && is_file($fileAbsolute)) {
                        unlink($fileAbsolute); // La fonction qui supprime le fichier 
                    }
                }

                $entityManager->remove($gameToDelete);
                $entityManager->flush();
                $this->addFlash('succès', 'Le jeu a été supprimé.');

            } catch (\Exception $exception) {
                $this->addFlash('succès', 'Une erreur est survenue. Veuillez réessayer.');
            }

            return $this->redirectToRoute('app_game_management');
        }

        return $this->render('admin/game_management/index.html.twig', [
            'game' => $game,
            // Quand tu es sur la page "Ajouter", il envoie le formulaire d'ajout et null pour l'édition.
            'formNew'  => $formNew ? $formNew->createView() : null, 
            // Quand tu es sur la page "Modifier", il envoie le formulaire d'édition et null pour l'ajout.
            'formEdit' => $formEdit ? $formEdit->createView() : null,
            'editId' => $editId,
        ]);
    }
}
