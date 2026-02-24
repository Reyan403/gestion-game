<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentaryFixtures extends Fixture
{
    public function data(): array 
    {
        return [
            [
                'description' => 'Un jeu captivant avec une histoire immersive et des personnages attachants. L’aventure est prenante du début à la fin.'
            ],
            [
                'description' => 'Les graphismes sont magnifiques et l’univers est très bien détaillé, mais la difficulté peut parfois sembler déséquilibrée.'
            ],
            [
                'description' => 'Un gameplay dynamique et intuitif qui offre une excellente prise en main, même pour les nouveaux joueurs.'
            ],
            [
                'description' => 'La bande-son est exceptionnelle et accompagne parfaitement les moments forts du jeu.'
            ],
            [
                'description' => 'Le mode multijoueur ajoute une vraie plus-value avec des parties intenses et compétitives.'
            ],
            [
                'description' => 'Malgré quelques bugs mineurs, l’expérience globale reste très agréable et divertissante.'
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
