<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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
                'image' => 'img/zelda.jpg',
                'game' => self::ZELDA,
                'genres' => [
                    CategoryFixtures::ACTION, 
                    CategoryFixtures::AVENTURE, 
                ]
            ],
            [
                'title' => 'Minecraft',
                'description' => 'Créez, explorez et survivez dans un monde ouvert composé de blocs, où l’imagination est la seule limite.',
                'image' => 'img/minecraft.jpg',
                'game' => self::MINECRAFT,
                'genres' => [
                    CategoryFixtures::AVENTURE, 
                ]
            ],
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'description' => 'Incarnez Geralt de Riv, chasseur de monstres, dans un RPG riche en quêtes, choix moraux et combats intenses dans un univers médiéval fantastique.',
                'image' => 'img/the-witcher.webp',
                'game' => self::THE_WITCHER,
                'genres' => [
                    CategoryFixtures::RPG, 
                    CategoryFixtures::AVENTURE, 
                    CategoryFixtures::ACTION, 
                ]
            ],
            [
                'title' => 'Fortnite',
                'description' => 'Participez à des batailles multijoueur intenses jusqu’au dernier survivant, construisez des structures et défiez vos amis dans des combats dynamiques.',
                'image' => 'img/fortnite.jpg',
                'game' => self::FORTNITE,
                'genres' => [
                    CategoryFixtures::ACTION, 
                    CategoryFixtures::BATTLE_ROYALE, 
                ]
            ],
            [
                'title' => 'Cyberpunk 2077',
                'description' => 'Plongez dans Night City, une métropole futuriste, et personnalisez votre personnage pour accomplir des missions dans un monde ouvert cyberpunk.',
                'image' => 'img/Cyberpunk2077.webp',
                'game' => self::CYBERPUNK,
                'genres' => [
                    CategoryFixtures::ACTION, 
                    CategoryFixtures::RPG, 
                    CategoryFixtures::FPS, 
                ]
            ],
            [
                'title' => 'Resident Evil Village',
                'description' => 'Plongez dans une horreur intense avec Ethan Winters qui doit survivre dans un village rempli de monstres et découvrir les secrets terrifiants de la famille Dimitrescu.',
                'image' => 'img/resident-evil.jpg',
                'game' => self::RESIDENT_EVIL,
                'genres' => [
                    CategoryFixtures::HORREUR, 
                ]
            ],
            [
                'title' => 'Grand Theft Auto V (GTA V)',
                'description' => 'Vivez une aventure criminelle à Los Santos, avec missions scénarisées, exploration libre et une multitude d’activités dans un monde ouvert vivant.',
                'image' => 'img/GTA.avif',
                'game' => self::GTA,
                'genres' => [
                    CategoryFixtures::ACTION, 
                    CategoryFixtures::OPEN_WORLD, 
                ]
            ],
            [
                'title' => 'Overwatch',
                'description' => 'Choisissez un héros aux compétences uniques et affrontez des équipes adverses dans des matchs rapides et tactiques.',
                'image' => 'img/overwatch.jpg',
                'game' => self::OVERWATCH ,
                'genres' => [
                    CategoryFixtures::ACTION, 
                    CategoryFixtures::FPS, 
                ]
            ],
            [
                'title' => 'Hollow Knight',
                'description' => 'Explorez les sombres et mystérieux royaumes de Hallownest, combattez des ennemis redoutables et découvrez l’histoire cachée de ce monde en 2D.',
                'image' => 'img/hollow-knight.jpg',
                'game' => self::HOLLOW_KNIGHT,
                'genres' => [
                    CategoryFixtures::AVENTURE, 
                ]
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); $i++) {
            $game = new Game();
            $game->setTitle(self::data()[$i]['title']);
            $game->setDescription(self::data()[$i]['description']);
            $game->setImage(self::data()[$i]['image']);

            $this->addReference(self::data()[$i]['game'], $game);

            if (isset(self::data()[$i]['genres'])) {
                for($j = 0; $j < count(self::data()[$i]['genres']); $j++) {
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
        ];
    }
}
