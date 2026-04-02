<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    public const ZELDA = 'zelda';
    public const MINECRAFT = 'minecraft';
    public const THE_WITCHER = 'the-witcher';
    public const FORTNITE = 'fortnite';
    public const CYBERPUNK = 'cyberpunk';
    public const RESIDENT_EVIL = 'resident-evil';
    public const GTA = 'gta';
    public const OVERWATCH = 'overwatch';
    public const HOLLOW_KNIGHT = 'hollow-knight';

    public static function data(): array
    {
        return [
            [
                'title' => 'The Legend of Zelda: Breath of the Wild',
                'description' => 'Explorez le vaste royaume d’Hyrule en résolvant des énigmes, combattant des ennemis et découvrant des secrets cachés dans ce jeu d’aventure épique.',
                'image' => 'img/zelda.webp',
                'createdAt' => new \DateTime('2025-01-28 16:20:00'),
                'isValidated' => true,
                'isArchived' => false,
                'pendingChange' => true,
                'whenIsValidated' => new \DateTime('2026-03-16 16:20:00'),
                'game' => self::ZELDA,
                'reference_user' => UserFixtures::USER_3,
                'reference_user_validated_by' => UserFixtures::USER_1,
                'genres' => [
                    CategoryFixtures::ACTION,
                    CategoryFixtures::AVENTURE,
                ],
            ],
            [
                'title' => 'Minecraft',
                'description' => 'Créez, explorez et survivez dans un monde ouvert composé de blocs, où l’imagination est la seule limite.',
                'image' => 'img/minecraft.webp',
                'createdAt' => new \DateTime('2024-01-28 16:20:00'),
                'isValidated' => false,
                'isArchived' => false,
                'pendingChange' => false,
                'game' => self::MINECRAFT,
                'reference_user' => UserFixtures::USER_1,
                'genres' => [
                    CategoryFixtures::AVENTURE,
                ],
            ],
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'description' => 'Incarnez Geralt de Riv, chasseur de monstres, dans un RPG riche en quêtes, choix moraux et combats intenses dans un univers médiéval fantastique.',
                'image' => 'img/thewitcher.webp',
                'createdAt' => new \DateTime('2023-01-28 16:20:00'),
                'isValidated' => false,
                'isArchived' => true,
                'pendingChange' => false,
                'game' => self::THE_WITCHER,
                'reference_user' => UserFixtures::USER_3,
                'genres' => [
                    CategoryFixtures::RPG,
                    CategoryFixtures::AVENTURE,
                    CategoryFixtures::ACTION,
                ],
            ],
            [
                'title' => 'Fortnite',
                'description' => 'Participez à des batailles multijoueur intenses jusqu’au dernier survivant, construisez des structures et défiez vos amis dans des combats dynamiques.',
                'image' => 'img/fortnite.webp',
                'createdAt' => new \DateTime('2022-01-28 16:20:00'),
                'isValidated' => true,
                'isArchived' => false,
                'pendingChange' => false,
                'game' => self::FORTNITE,
                'reference_user' => UserFixtures::USER_1,
                'genres' => [
                    CategoryFixtures::ACTION,
                    CategoryFixtures::BATTLE_ROYALE,
                ],
            ],
            [
                'title' => 'Cyberpunk 2077',
                'description' => 'Plongez dans Night City, une métropole futuriste, et personnalisez votre personnage pour accomplir des missions dans un monde ouvert cyberpunk.',
                'image' => 'img/cyberpunk.webp',
                'createdAt' => new \DateTime('2021-01-28 16:20:00'),
                'isValidated' => false,
                'isArchived' => false,
                'pendingChange' => false,
                'game' => self::CYBERPUNK,
                'reference_user' => UserFixtures::USER_3,
                'genres' => [
                    CategoryFixtures::ACTION,
                    CategoryFixtures::RPG,
                    CategoryFixtures::FPS,
                ],
            ],
            [
                'title' => 'Resident Evil Village',
                'description' => 'Plongez dans une horreur intense avec Ethan Winters qui doit survivre dans un village rempli de monstres et découvrir les secrets terrifiants de la famille Dimitrescu.',
                'image' => 'img/residentevil.webp',
                'createdAt' => new \DateTime('2020-01-28 16:20:00'),
                'isValidated' => false,
                'isArchived' => true,
                'pendingChange' => false,
                'game' => self::RESIDENT_EVIL,
                'reference_user' => UserFixtures::USER_1,
                'genres' => [
                    CategoryFixtures::HORREUR,
                ],
            ],
            [
                'title' => 'Grand Theft Auto V (GTA V)',
                'description' => 'Vivez une aventure criminelle à Los Santos, avec missions scénarisées, exploration libre et une multitude d’activités dans un monde ouvert vivant.',
                'image' => 'img/gta.webp',
                'createdAt' => new \DateTime('2019-01-28 16:20:00'),
                'isValidated' => true,
                'isArchived' => false,
                'pendingChange' => true,
                'whenIsValidated' => new \DateTime('2026-03-17 16:20:00'),
                'game' => self::GTA,
                'reference_user' => UserFixtures::USER_3,
                'reference_user_validated_by' => UserFixtures::USER_1,
                'genres' => [
                    CategoryFixtures::ACTION,
                    CategoryFixtures::OPEN_WORLD,
                ],
            ],
            [
                'title' => 'Overwatch',
                'description' => 'Choisissez un héros aux compétences uniques et affrontez des équipes adverses dans des matchs rapides et tactiques.',
                'image' => 'img/overwatch.webp',
                'createdAt' => new \DateTime('2018-01-28 16:20:00'),
                'isValidated' => true,
                'isArchived' => false,
                'pendingChange' => false,
                'whenIsValidated' => new \DateTime('2026-03-18 16:20:00'),
                'game' => self::OVERWATCH,
                'reference_user' => UserFixtures::USER_1,
                'reference_user_validated_by' => UserFixtures::USER_3,
                'genres' => [
                    CategoryFixtures::ACTION,
                    CategoryFixtures::FPS,
                ],
            ],
            [
                'title' => 'Hollow Knight',
                'description' => 'Explorez les sombres et mystérieux royaumes de Hallownest, combattez des ennemis redoutables et découvrez l’histoire cachée de ce monde en 2D.',
                'image' => 'img/hollow-knight.jpg',
                'createdAt' => new \DateTime('2017-01-28 16:20:00'),
                'isValidated' => false,
                'isArchived' => false,
                'pendingChange' => false,
                'game' => self::HOLLOW_KNIGHT,
                'reference_user' => UserFixtures::USER_3,
                'genres' => [
                    CategoryFixtures::AVENTURE,
                ],
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); ++$i) {
            $game = new Game();
            $game->setTitle(self::data()[$i]['title']);
            $game->setDescription(self::data()[$i]['description']);
            $game->setImage(self::data()[$i]['image']);
            $game->setDateCreated(self::data()[$i]['createdAt']);
            $game->setIsValidated(self::data()[$i]['isValidated']);
            $game->setIsArchived(self::data()[$i]['isArchived']);
            $game->setPendingChange(self::data()[$i]['pendingChange']);

            $this->addReference(self::data()[$i]['game'], $game);

            $game->setUser($this->getReference(self::data()[$i]['reference_user'], User::class));

            if (isset(self::data()[$i]['whenIsValidated'])) {
                $game->setWhenIsValidated(self::data()[$i]['whenIsValidated']);
            }

            if (isset(self::data()[$i]['reference_user_validated_by'])) {
                $game->setIsValidatedBy($this->getReference(self::data()[$i]['reference_user_validated_by'], User::class));
            }

            if (isset(self::data()[$i]['genres'])) {
                for ($j = 0; $j < count(self::data()[$i]['genres']); ++$j) {
                    $game->addCategory($this->getReference(self::data()[$i]['genres'][$j], Category::class));
                }
            }

            $manager->persist($game);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}