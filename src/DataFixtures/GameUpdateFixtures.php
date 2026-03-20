<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\GameUpdate;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameUpdateFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array
    {
        return [
            [
                'title' => 'Zelda: Aventurier d’Hyrule',
                'description' => 'Parcourez le royaume d’Hyrule, résolvez des énigmes, combattez des créatures et découvrez des secrets dans cette aventure épique.',
                'image' => 'img/zelda.jpg',
                'updatedAt' => new \DateTime('2023-01-28 16:20:00'),
                'reference_user' => UserFixtures::USER_1,
                'reference_status' => StatusFixtures::PENDING,
                'game' => GameFixtures::ZELDA,
                'genres' => [
                    CategoryFixtures::HORREUR,
                    CategoryFixtures::ACTION,
                ],
            ],
            [
                'title' => 'Minecraft: Monde Infini',
                'description' => 'Construisez, explorez et survivez dans un univers en blocs sans limites où votre imagination est votre seule arme.',
                'image' => 'img/minecraft.jpg',
                'updatedAt' => new \DateTime('2022-01-28 16:20:00'),
                'reference_user' => UserFixtures::USER_3,
                'reference_status' => StatusFixtures::PENDING,
                'game' => GameFixtures::MINECRAFT,
                'genres' => [
                    CategoryFixtures::RPG,
                    CategoryFixtures::ACTION,
                ],
            ],
            [
                'title' => 'The Witcher 3: Sorceleur',
                'description' => 'Incarnez Geralt, chasseur de monstres, dans un RPG immersif avec des choix moraux, des quêtes épiques et des combats intenses dans un monde médiéval fantastique.',
                'image' => 'img/the-witcher.webp',
                'updatedAt' => new \DateTime('2021-01-28 16:20:00'),
                'reference_user' => UserFixtures::USER_1,
                'reference_status' => StatusFixtures::PENDING,
                'game' => GameFixtures::THE_WITCHER,
                'genres' => [
                    CategoryFixtures::HORREUR,
                    CategoryFixtures::COURSE,
                    CategoryFixtures::ACTION,
                ],
            ],
            [
                'title' => 'Fortnite: Batailles Constructives',
                'description' => 'Affrontez vos amis dans des batailles intenses, construisez des structures stratégiques et devenez le dernier survivant.',
                'image' => 'img/fortnite.jpg',
                'updatedAt' => new \DateTime('2020-01-28 16:20:00'),
                'reference_user' => UserFixtures::USER_3,
                'reference_status' => StatusFixtures::PENDING,
                'game' => GameFixtures::FORTNITE,
                'genres' => [
                    CategoryFixtures::OPEN_WORLD,
                    CategoryFixtures::BATTLE_ROYALE,
                ],
            ],
            [
                'title' => 'Cyberpunk 2077: Night City',
                'description' => 'Explorez Night City, personnalisez votre personnage et accomplissez des missions dans un monde ouvert cyberpunk ultra-détaillé.',
                'image' => 'img/Cyberpunk2077.webp',
                'updatedAt' => new \DateTime('2019-01-28 16:20:00'),
                'reference_user' => UserFixtures::USER_1,
                'reference_status' => StatusFixtures::PENDING,
                'game' => GameFixtures::CYBERPUNK,
                'genres' => [
                    CategoryFixtures::HORREUR,
                    CategoryFixtures::ACTION,
                    CategoryFixtures::COURSE,
                ],
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); ++$i) {
            $gameUpdate = new GameUpdate();
            $gameUpdate->setTitle(self::data()[$i]['title']);
            $gameUpdate->setDescription(self::data()[$i]['description']);
            $gameUpdate->setImage(self::data()[$i]['image']);
            $gameUpdate->setDateUpdated(self::data()[$i]['updatedAt']);

            $gameUpdate->setUser($this->getReference(self::data()[$i]['reference_user'], User::class));
            $gameUpdate->setStatus($this->getReference(self::data()[$i]['reference_status'], Status::class));
            $gameUpdate->setGame($this->getReference(self::data()[$i]['game'], Game::class));

            if (isset(self::data()[$i]['genres'])) {
                for ($j = 0; $j < count(self::data()[$i]['genres']); ++$j) {
                    $gameUpdate->addCategory($this->getReference(self::data()[$i]['genres'][$j], Category::class));
                }
            }

            $manager->persist($gameUpdate);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            StatusFixtures::class,
            GameFixtures::class,
        ];
    }
}
