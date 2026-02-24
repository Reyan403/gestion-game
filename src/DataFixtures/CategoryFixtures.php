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
            ],
            [
                'name' => 'FPS',
                'genre' => self::FPS,
            ],
            [
                'name' => 'Course',
                'genre' => self::COURSE,
            ],
            [
                'name' => 'Horreur',
                'genre' => self::HORREUR,
            ],
            [
                'name' => 'Aventure',
                'genre' => self::AVENTURE,
            ],
            [
                'name' => 'Action',
                'genre' => self::ACTION,
            ],
            [
                'name' => 'Battle Royale',
                'genre' => self::BATTLE_ROYALE,
            ],
            [
                'name' => 'Open World',
                'genre' => self::OPEN_WORLD,
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < count(self::data()); $i++) {
            $category = new Category();
            $category->setName(self::data()[$i]['name']);

            $this->addReference(self::data()[$i]['genre'], $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
