<?php

namespace App\Controller\admin;

use App\Entity\Game;
use App\Entity\GameUpdate;
use App\Form\GameType;
use App\Form\GameUpdateType;
use App\Repository\GameRepository;
use App\Security\RightVoter;
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
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        if (!$this->isGranted(RightVoter::GAME_CREATE) && !$this->isGranted(RightVoter::GAME_EDIT) && !$this->isGranted(RightVoter::GAME_VALIDATE) && !$this->isGranted(RightVoter::GAME_DELETE)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        if($this->isGranted(RightVoter::GAME_VALIDATE)) {
            $gamesDisplayed = $gameRepository->findBy([
                'isValidated' => true,
                'isArchived' => false,
                'pendingChange' => false,
            ]);
        } else if ($this->isGranted(RightVoter::GAME_CREATE) || $this->isGranted(RightVoter::GAME_EDIT) || $this->isGranted(RightVoter::GAME_DELETE)) {
            $gamesDisplayed = $gameRepository->findBy([
                'user' => $this->getUser(),
                'isValidated' => true,
                'isArchived' => false,
                'pendingChange' => false,
            ]);
        }

        // On va chercher l'id du jeu dans l'URL
        $editId = $request->query->get('editId');
        $deleteId = $request->query->get('deleteId');

        $formEdit = null;
        $formNew = null;
        $openNewGamePopup = false;

        if (!$editId && $this->isGranted(RightVoter::GAME_CREATE)) {
            
            $newGame = new Game();
            $formNew = $this->createForm(GameType::class, $newGame);
            $formNew->handleRequest($request);

            if ($formNew->isSubmitted()) {
                if ($formNew->isValid()) {
                    // On vérifie si un nouveau fichier a été envoyé via le champ 'image' du formulaire
                    if ($image = $formNew['image']->getData()) {
                        // On génère un nom de fichier unique (ex: 65f4a1b2c3.jpg) pour éviter les doublons sur le serveur
                        $fileName = uniqid().'.'.$image->guessExtension();

                        // On déplace physiquement le fichier du dossier temporaire vers ton dossier de destination (public/...)
                        $image->move($imageDir, $fileName);

                        // On met à jour le nom du fichier dans l'objet Game en y ajoutant le préfixe 'img/' pour la base de données
                        $newGame->setImage('img/'.$fileName);
                    }

                    $existingGame = $gameRepository->findOneBy(['title' => $newGame->getTitle()]);
                    if ($existingGame) {
                        $this->addFlash('erreur', 'Un jeu avec ce titre existe déjà.');
                        $openNewGamePopup = true;
                    } else {
                        try {
                            $newGame->setIsValidated(false);
                            $newGame->setIsArchived(false);
                            $newGame->setUser($this->getUser());
                            $newGame->setDateCreated(new \DateTime('now'));

                            $entityManager->persist($newGame);
                            $entityManager->flush();
                            $this->addFlash('succès', 'Le jeu est en cours de modération.');

                            return $this->redirectToRoute('app_game_management');
                        } catch (\Exception $exception) {
                            $this->addFlash('erreur', 'Une erreur est survenue. Veuillez réessayer.');
                            $openNewGamePopup = true;
                        }
                    }
                } else {
                    $this->addFlash('erreur', 'Le formulaire est invalide.');
                    $openNewGamePopup = true;
                }
            }
        } elseif ($editId) {

            $this->denyAccessUnlessGranted(RightVoter::GAME_EDIT);

            // On vérifie que l'id de ce jeu existe déjà
            $editGame = $gameRepository->find($editId);

            if (!$editGame) {
                $this->addFlash('erreur', 'Le jeu demandé n\'existe pas.');
                return $this->redirectToRoute('app_game_management');
                // Ou tu peux utiliser : throw $this->createNotFoundException('Jeu introuvable');
            }

            $gameUpdate = new GameUpdate();
            $gameUpdate->setGame($editGame);
            $gameUpdate->setUser($this->getUser());

            // Copier les données existantes dans GameUpdate pour pré-remplir le formulaire
            $gameUpdate->setTitle($editGame->getTitle());
            $gameUpdate->setDescription($editGame->getDescription());
            $gameUpdate->setImage($editGame->getImage());

            // Si tu veux aussi copier les catégories
            foreach ($editGame->getCategories() as $category) {
                $gameUpdate->addCategory($category);
            }

            $formEdit = $this->createForm(GameUpdateType::class, $gameUpdate);
            $formEdit->handleRequest($request);

            if ($formEdit->isSubmitted()) {
                if ($formEdit->isValid()) {
                    if ($image = $formEdit['image']->getData()) {
                        $fileName = uniqid().'.'.$image->guessExtension();
                        $image->move($imageDir, $fileName);
                        $gameUpdate->setImage('img/'.$fileName);
                    }

                    // Il faut que je vérifie que le titre envoyé dans le formulaire n'est pas égal à un titre déjà existant dans la table Game
                    $newTitle = $gameUpdate->getTitle();
                    $existingGame = $gameRepository->findOneBy(['title' => $newTitle]);

                    // On vérifie que le jeu existe déjà avec ce titre et on compare les ID pour vérifier que ce n'est pas le même jeu qu'on modifie
                    // Si on ne met pas cette comparaison, il me dit que le titre existe déjà alors que je n'ai pas touché au titre
                    if ($existingGame && $existingGame->getId() !== $gameUpdate->getGame()->getId()) {
                        $this->addFlash('erreur', 'Un jeu avec ce titre existe déjà.');

                        return $this->redirectToRoute('app_game_management', ['editId' => $editId]);
                    }
                    try {
                        $gameUpdate->setDateUpdated(new \DateTime('now'));
                        $editGame->setPendingChange(true);

                        $entityManager->persist($gameUpdate);
                        $entityManager->flush();
                        $this->addFlash('succès', 'Le jeu est en cours de modification.');

                        return $this->redirectToRoute('app_game_management');
                    } catch (\Exception $exception) {
                        $this->addFlash('erreur', 'Une erreur est survenue.' . $exception->getMessage());
                    }
                } else {
                    $this->addFlash('erreur', 'Le formulaire est invalide.');
                }
            }
        }

        if ($deleteId) {

            $this->denyAccessUnlessGranted(RightVoter::GAME_DELETE);

            $gameToDelete = $gameRepository->find($deleteId);

            try {
                $imagePath = $gameToDelete->getImage();

                if ($imagePath) {
                    // On utilise basename() pour récupérer juste "mon-image.jpg" sans le "img/"
                    $fileName = basename($imagePath);

                    // On construit le chemin absolu vers le fichier sur le disque
                    $fileAbsolute = $imageDir.'/'.$fileName;

                    // On vérifie que le fichier existe bien avant de le supprimer
                    if (file_exists($fileAbsolute) && is_file($fileAbsolute)) {
                        unlink($fileAbsolute); // La fonction qui supprime le fichier
                    }
                }

                $entityManager->remove($gameToDelete);
                $entityManager->flush();
                $this->addFlash('succès', 'Le jeu a été supprimé.');
            } catch (\Exception $exception) {
                $this->addFlash('erreur', 'Une erreur est survenue. Veuillez réessayer.');
            }

            return $this->redirectToRoute('app_game_management');
        }

        return $this->render('admin/game_management/index.html.twig', [
            'game' => $gamesDisplayed,
            // Quand tu es sur la page "Ajouter", il envoie le formulaire d'ajout et null pour l'édition.
            'formNew' => $formNew ? $formNew->createView() : null,
            // Quand tu es sur la page "Modifier", il envoie le formulaire d'édition et null pour l'ajout.
            'formEdit' => $formEdit ? $formEdit->createView() : null,
            'editId' => $editId,
            'openNewGamePopup' => $openNewGamePopup,
        ]);
    }
}
