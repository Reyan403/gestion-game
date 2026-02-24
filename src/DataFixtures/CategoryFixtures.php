<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function data(): array
    {
        return [
            [
                'name' => 'RPG',
            ],
            [
                'name' => 'FPS',
            ]
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < count(self::data()); $i++) {
            $category = new Category();
            $category->setName(self::data()[$i]['name']);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
