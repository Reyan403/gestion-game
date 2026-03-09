<?php

namespace App\DataFixtures;

use App\Entity\Commentary;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentaryFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array 
    {
        return [
            [
                'description' => 'Un jeu captivant avec une histoire immersive et des personnages attachants. L’aventure est prenante du début à la fin.',
                'reference_user' => UserFixtures::USER_4,
                'game' => GameFixtures::ZELDA,
                'createdAt' => new \DateTime('2023-10-15 14:30:00'),
                'isValidated' => false,
                'isArchived' => false,
            ],
            [
                'description' => 'Les graphismes sont magnifiques et l’univers est très bien détaillé, mais la difficulté peut parfois sembler déséquilibrée.',
                'reference_user' => UserFixtures::USER_5,
                'game' => GameFixtures::OVERWATCH,
                'createdAt' => new \DateTime('2023-11-02 09:15:00'),
                'isValidated' => false,
                'isArchived' => false,
            ],
            [
                'description' => 'Un gameplay dynamique et intuitif qui offre une excellente prise en main, même pour les nouveaux joueurs.',
                'reference_user' => UserFixtures::USER_4,
                'game' => GameFixtures::GTA,
                'createdAt' => new \DateTime('2023-12-10 21:00:00'),
                'isValidated' => true,
                'isArchived' => false,
            ],
            [
                'description' => 'La bande-son est exceptionnelle et accompagne parfaitement les moments forts du jeu.',
                'reference_user' => UserFixtures::USER_5,
                'game' => GameFixtures::GTA,
                'createdAt' => new \DateTime('2024-01-05 18:45:00'),
                'isValidated' => false,
                'isArchived' => true,
            ],
            [
                'description' => 'Le mode multijoueur ajoute une vraie plus-value avec des parties intenses et compétitives.',
                'reference_user' => UserFixtures::USER_5,
                'game' => GameFixtures::HOLLOW_KNIGHT,
                'createdAt' => new \DateTime('2024-01-20 16:20:00'),
                'isValidated' => false,
                'isArchived' => true,
            ],
            [
                'description' => 'Malgré quelques bugs mineurs, l’expérience globale reste très agréable et divertissante.',
                'reference_user' => UserFixtures::USER_6,
                'game' => GameFixtures::ZELDA,
                'createdAt' => new \DateTime('2023-10-15 12:30:00'),
                'isValidated' => true,
                'isArchived' => false,
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); $i++) {
            $commentary = new Commentary();
            $commentary->setDescription(self::data()[$i]['description']);
            $commentary->setDate(self::data()[$i]['createdAt']);
            $commentary->setIsValidated(self::data()[$i]['isValidated']);
            $commentary->setIsArchived(self::data()[$i]['isArchived']);
            $commentary->setGame($this->getReference(self::data()[$i]['game'], Game::class));
            $commentary->setUser($this->getReference(self::data()[$i]['reference_user'], User::class));

            $manager->persist($commentary);
        }

        $manager->flush();
    }

    public function getDependencies(): array 
    {
        return [
            GameFixtures::class,
            UserFixtures::class,
        ];
    }
}
