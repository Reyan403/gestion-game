<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\OneToMany(targetEntity: Commentary::class, mappedBy: 'game')]
    private Collection $commentaries;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'games')]
    private Collection $categories;

    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'game')]
    private Collection $notes;

    public function __construct() 
    {
        $this->commentaries = newArrayCollection();
        $this->categories = newArrayCollection();
        $this->notes = newArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle() : string 
    {
        return $this->title;
    }

    public function getDescription() : string 
    {
        return $this->description;
    }

    public function setTitle(string $newTitle) : void 
    {
        $this->title = $newTitle;
    }

    public function setDescription(string $newDescription) : void 
    {
        $this->description = $newDescription;
    }
}
