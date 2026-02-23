<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'categories')]
    private Collection $games;

    public function __construct() 
    {
        $this->games = newArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName() : string 
    {
        return $this->name;
    } 

    public function setName(string $newName) : void 
    {
        $this->name = $newName;
    }
}
