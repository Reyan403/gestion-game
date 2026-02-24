<?php

namespace App\DataFixtures;

use App\Entity\Note;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NoteFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array {
        return [
            [
                'note_game' => 4,
                'reference_user' => UserFixtures::USER_6,
                'game' => GameFixtures::ZELDA,
            ],
            [
                'note_game' => 2,
                'reference_user' => UserFixtures::USER_4,
                'game' => GameFixtures::OVERWATCH,
            ],
            [
                'note_game' => 3,
                'reference_user' => UserFixtures::USER_5,
                'game' => GameFixtures::OVERWATCH,
            ],
            [
                'note_game' => 5,
                'reference_user' => UserFixtures::USER_6,
                'game' => GameFixtures::OVERWATCH,
            ],
            [
                'note_game' => 1,
                'reference_user' => UserFixtures::USER_4,
                'game' => GameFixtures::ZELDA,
            ],
            [
                'note_game' => 5,
                'reference_user' => UserFixtures::USER_5,
                'game' => GameFixtures::ZELDA,
            ],
            [
                'note_game' => 5,
                'reference_user' => UserFixtures::USER_6,
                'game' => GameFixtures::GTA,
            ],
            [
                'note_game' => 2,
                'reference_user' => UserFixtures::USER_4,
                'game' => GameFixtures::GTA,
            ],
            [
                'note_game' => 1,
                'reference_user' => UserFixtures::USER_5,
                'game' => GameFixtures::GTA,
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); $i++) {
            $note = new Note();
            $note->setNoteGame(self::data()[$i]['note_game']);  
            $note->setGame($this->getReference(self::data()[$i]['game'], Game::class));
            $note->setUser($this->getReference(self::data()[$i]['reference_user'], User::class));

            $manager->persist($note);
        }

        $manager->flush();
    }

    public function getDependencies(): array 
    {
        return [
            UserFixtures::class,
            GameFixtures::class,
        ];
    }
}
