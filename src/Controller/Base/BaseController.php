<?php

namespace App\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class BaseController extends AbstractController  
{
    // Fonction pour vérifier que l'utilisateur est bien connecté avant d'accéder à tel page
    protected function requireLogin(): ?RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        return null;
    }

    // Fonction pour upload une image lors d'un ajout ou la modification d'un jeu
    protected function uploadImage(object $entity, string $imageDir, FormInterface $form): void
    {
        // On vérifie si un nouveau fichier a été envoyé via le champ 'image' du formulaire
        if ($image = $form['image']->getData()) {

            // On génère un nom de fichier unique (ex: 65f4a1b2c3.jpg) pour éviter les doublons sur le serveur
            $fileName = uniqid().'.'.$image->guessExtension();

            // On déplace physiquement le fichier du dossier temporaire vers ton dossier de destination (public/...)
            $image->move($imageDir, $fileName);

            // On met à jour le nom du fichier dans l'objet Game en y ajoutant le préfixe 'img/' pour la base de données
            $entity->setImage('img/'.$fileName);
        }
    }

    // Retourne le nom du bouton soumis parmi la liste donnée, ou null si aucun
    // Pourquoi array $buttons ? Car on peut avoir plusieurs boutons à vérifier 
    protected function getClickedButton(FormInterface $form, array $buttons): ?string
    {
        foreach ($buttons as $name) {
            /** @var SubmitButton $btn */
            $btn = $form->get($name);
            if ($btn->isClicked()) {
                return $name;
            }
        }

        return null;
    }
}
