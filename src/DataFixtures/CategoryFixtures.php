<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const RPG = 'rpg';
    public const FPS = 'fps';
    public const COURSE = 'course';
    public const HORREUR = 'horreur';
    public const AVENTURE = 'aventure';
    public const ACTION = 'action';
    public const BATTLE_ROYALE = 'battle-royale';
    public const OPEN_WORLD = 'open-world';

    public static function data(): array
    {
        return [
            [
                'name' => 'RPG',
                'genre' => self::RPG,
                'twitch_game_id' => '38202,21027,206793,71375',
            ],
            [
                'name' => 'FPS',
                'genre' => self::FPS,
                'twitch_game_id' => '516575,32399,515025',
            ],
            [
                'name' => 'Course',
                'genre' => self::COURSE,
                'twitch_game_id' => '504461,33214,313554',
            ],
            [
                'name' => 'Horreur',
                'genre' => self::HORREUR,
                'twitch_game_id' => '512710,115243,18834',
            ],
            [
                'name' => 'Aventure',
                'genre' => self::AVENTURE,
                'twitch_game_id' => '493057,497078,518204',
            ],
            [
                'name' => 'Action',
                'genre' => self::ACTION,
                'twitch_game_id' => '21779,509658,162502',
            ],
            [
                'name' => 'Battle Royale',
                'genre' => self::BATTLE_ROYALE,
                'twitch_game_id' => '33214,511224,491487',
            ],
            [
                'name' => 'Open World',
                'genre' => self::OPEN_WORLD,
                'twitch_game_id' => '32982,27471,167805',
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); ++$i) {
            $category = new Category();
            $category->setName(self::data()[$i]['name']);
            $category->setTwitchGameId(self::data()[$i]['twitch_game_id']);

            $this->addReference(self::data()[$i]['genre'], $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
