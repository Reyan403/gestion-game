<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const REFUSED = 'refused';

    public static function data(): array
    {
        return [
            [
                "name" => "pending",
                'reference_status' => self::PENDING,
            ],
            [
                "name" => "accepted",
                'reference_status' => self::ACCEPTED,
            ],
            [
                "name" => "refused",
                'reference_status' => self::REFUSED,
            ],
        ];
        
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::data()); $i++) {
            $status = new Status();
            $status->setName(self::data()[$i]['name']);

            $this->addReference(self::data()[$i]['reference_status'], $status);

            $manager->persist($status);
        }

        $manager->flush();
    }
}
