<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RightVoter extends Voter
{
    // --- GESTION DES RÔLES ---
    public const ROLE_VIEW   = 'Voir les rôles';
    public const ROLE_CREATE = 'Création d\'un rôle';
    public const ROLE_EDIT   = 'Modification d\'un rôle';
    public const ROLE_DELETE = 'Suppression d\'un rôle';
    public const ROLE_ASSIGN = 'Attribuer un rôle';

    // --- GESTION DES FICHES DE JEU ---
    public const GAME_CREATE   = 'Création de fiche de jeu';
    public const GAME_EDIT     = 'Modification d\'une fiche de jeu';
    public const GAME_DELETE   = 'Suppression d\'une fiche de jeu';
    public const GAME_VALIDATE = 'Validation d\'une fiche de jeu';

    // --- GESTION DES COMMENTAIRES ---
    public const COMMENT_CREATE   = 'Créer un commentaire';
    public const COMMENT_DELETE   = 'Suppression d\'un commentaire';
    public const COMMENT_VALIDATE = 'Validation d\'un commentaire';

    // --- GESTION DES UTILISATEURS ---
    public const USER_DELETE = 'Suppression d\'un utilisateur';

    /**
     * Détermine si ce Voter doit traiter la demande
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
            self::ROLE_VIEW, self::ROLE_CREATE, self::ROLE_EDIT, self::ROLE_DELETE, self::ROLE_ASSIGN,
            self::GAME_CREATE, self::GAME_EDIT, self::GAME_DELETE, self::GAME_VALIDATE,
            self::COMMENT_CREATE, self::COMMENT_DELETE, self::COMMENT_VALIDATE,
            self::USER_DELETE
        ]);
    }

    /**
     * Fait la vérification en base de données
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {

        $user = $token->getUser();

        // Verifie si l'utilisateur est connecté
        if (!$user instanceof User) {
            return false;
        }

        // On boucle sur les entités Rôles de l'utilisateur
        foreach ($user->getRolesEntities() as $role) {
            
            // On boucle sur les droits de chaque rôle
            foreach ($role->getRights() as $right) {
                
                // Si le nom du droit correspond à la constante demandée
                if ($right->getName() === $attribute) {
                    return true;
                }
            }
        }

        return false;
    }
}