<?php

namespace App\DataFixtures;

use App\Entity\Right;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RightFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array
    {
        return [
            [
                'name' => 'Création de fiche de jeu',
                'reference_role' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Modification d\'une fiche de jeu',
                'reference_role' => [
                    RoleFixtures::ROLE_MODERATEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'une fiche de jeu',
                'reference_role' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Validation d\'une fiche de jeu',
                'reference_role' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Créer un commentaire',
                'reference_role' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Validation d\'un commentaire',
                'reference_role' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'un commentaire',
                'reference_role' => [
                    RoleFixtures::ROLE_REDACTEUR,
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'un utilisateur',
                'reference_role' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Création d\'un rôle',
                'reference_role' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Modification d\'un rôle',
                'reference_role' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Suppression d\'un rôle',
                'reference_role' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Attribuer un rôle',
                'reference_role' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
            [
                'name' => 'Voir les rôles',
                'reference_role' => [
                    RoleFixtures::ROLE_ADMIN,
                ],
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); ++$i) {
            $right = new Right();
            $right->setName(self::data()[$i]['name']);

            if (isset(self::data()[$i]['reference_role'])) {
                for ($j = 0; $j < count(self::data()[$i]['reference_role']); ++$j) {
                    $right->addRole($this->getReference(self::data()[$i]['reference_role'][$j], Role::class));
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
