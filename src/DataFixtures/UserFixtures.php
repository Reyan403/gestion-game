<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER_1 = 'user-1';
    public const USER_2 = 'user-2';
    public const USER_3 = 'user-3';
    public const USER_4 = 'user-4';
    public const USER_5 = 'user-5';
    public const USER_6 = 'user-6';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function data(): array
    {
        return [
            [
                'name' => 'admin',
                'mail' => 'admin@example.com',
                'password' => 'password123',
                'reference_user' => self::USER_1,
                'reference_role' => RoleFixtures::ROLE_ADMIN,
            ],
            [
                'name' => 'moderateur',
                'mail' => 'moderateur@example.com',
                'password' => 'emma2024',
                'reference_user' => self::USER_2,
                'reference_role' => RoleFixtures::ROLE_MODERATEUR,
            ],
            [
                'name' => 'redacteur',
                'mail' => 'redacteur@example.com',
                'password' => 'hugoSecure1',
                'reference_user' => self::USER_3,
                'reference_role' => RoleFixtures::ROLE_REDACTEUR,
            ],
            [
                'name' => 'Chloé Petit',
                'mail' => 'chloe.petit@example.com',
                'password' => 'chloePass',
                'reference_user' => self::USER_4,
                'reference_role' => RoleFixtures::ROLE_USER,
            ],
            [
                'name' => 'Nathan Robert',
                'mail' => 'nathan.robert@example.com',
                'password' => 'nathan456',
                'reference_user' => self::USER_5,
                'reference_role' => RoleFixtures::ROLE_USER,
            ],
            [
                'name' => 'Reyan Ghazzaoui',
                'mail' => 'ghazzaoui.reyan@example.com',
                'password' => 'reyan1234',
                'reference_user' => self::USER_6,
                'reference_role' => RoleFixtures::ROLE_USER,
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); ++$i) {
            $user = new User();
            $user->setName(self::data()[$i]['name']);
            $user->setMail(self::data()[$i]['mail']);
            $user->setPassword($this->hasher->hashPassword($user, self::data()[$i]['password']));

            $this->addReference(self::data()[$i]['reference_user'], $user);

            $user->addRole($this->getReference(self::data()[$i]['reference_role'], Role::class));

            $manager->persist($user);
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
