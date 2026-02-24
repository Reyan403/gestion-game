<?php

namespace App\DataFixtures;

use App\Entity\Right;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RightFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array 
    {
        return [
            [
                'name' => 'Création de fiche de jeu',
                'roles' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Modification d\'une fiche de jeu',
                'roles' => [
                    RoleFixtures::ROLE_MODERATEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'une fiche de jeu',
                'roles' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Validation d\'une fiche de jeu',
                'roles' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Créer un commentaire',
                'roles' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                    RoleFixtures::ROLE_REDACTEUR
                ],
            ],
            [
                'name' => 'Validation d\'un commentaire',
                'roles' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'un commentaire',
                'roles' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'un utilisateur',
                'roles' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Création d\'un rôle',
                'roles' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Modification d\'un rôle',
                'roles' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'un rôle',
                'roles' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Attribuer un rôle',
                'roles' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Voir les rôles',
                'roles' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); $i++) {
            $right = new Right();
            $right->setName(self::data()[$i]['name']);

            if (isset(self::data()[$i]['roles'])) {
                for ($j = 0; $j < count(self::data()[$i]['roles']); $j++) {
                    $right->addRole($this->getReference(self::data()[$i]['roles'][$j], Role::class));
                }
            }

            $manager->persist($right);
        }

        $manager->flush();
    }

    public function getDependencies(): array 
    {
        return [
            RoleFixtures::class,
        ];
    }
}
