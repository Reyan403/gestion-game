<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\GameUpdate;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameUpdateFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data() : array
    {
        return [
            [
                'title' => 'The Legend of Zelda: Tears of the Kingdom',
                'description' => 'Explorez les cieux et les immenses souterrains d\'Hyrule dans cette suite épique. De nouvelles capacités et une menace inédite vous attendent.',
                'image' => 'img/zelda.webp',
                'updated_at' => new \DateTime('2026-02-10 11:30:00'),
                'reference_user' => UserFixtures::USER_3,
                'game' => GameFixtures::ZELDA,
            ],
            [
                'title' => 'Grand Theft Auto Online: Entreprises Criminelles',
                'description' => 'Prenez le contrôle absolu de Los Santos. Fondez votre propre organisation, négociez avec des gangs rivaux et étendez votre empire illégal à travers tout l\'État.',
                'image' => 'img/gta.webp',
                'updated_at' => new \DateTime('2026-03-05 09:45:00'),
                'reference_user' => UserFixtures::USER_3,
                'game' => GameFixtures::GTA,
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < count(self::data()); $i++) {
            $gameUpdate = new GameUpdate();
            $gameUpdate->setTitle(self::data()[$i]['title']);
            $gameUpdate->setDescription(self::data()[$i]['description']);
            $gameUpdate->setImage(self::data()[$i]['image']);
            $gameUpdate->setDateUpdated(self::data()[$i]['updated_at']);

            $gameUpdate->setGame($this->getReference(self::data()[$i]['game'], Game::class));
            $gameUpdate->setUser($this->getReference(self::data()[$i]['reference_user'], User::class));

            $manager->persist($gameUpdate);
        }

        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [
            GameFixtures::class,
            UserFixtures::class,
        ];
    }
}
