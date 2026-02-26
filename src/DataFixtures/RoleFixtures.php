<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public const ROLE_MODERATEUR = 'role-moderateur';
    public const ROLE_ADMIN = 'role-admin';
    public const ROLE_REDACTEUR = 'role-redacteur';
    public const ROLE_USER = 'role-user';

    public static function data(): array 
    {
        return [
            [
                'name' => 'Modérateur',
                'reference_role' => self::ROLE_MODERATEUR,
            ],
            [
                'name' => 'Administrateur',
                'reference_role' => self::ROLE_ADMIN,
            ],
            [
                'name' => 'Rédacteur',
                'reference_role' => self::ROLE_REDACTEUR,
            ],
            [
                'name' => 'Utilisateur',
                'reference_role' => self::ROLE_USER,
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); $i++) {
            $role = new Role();
            $role->setName(self::data()[$i]['name']);

            $this->addReference(self::data()[$i]['reference_role'], $role);

            $manager->persist($role);
        }

        $manager->flush();
    }
}
