<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;

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
        $this->commentaries = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->notes = new ArrayCollection();
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

    // Méthodes des collection
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addCategory(Category $category): self 
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function addCommentary(Commentary $commentary): self 
    {
        if (!$this->commentaries->contains($commentary)) {
            $this->commentaries->add($commentary);
        }

        return $this;
    }

    public function addNote(Note $note): self 
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
        }

        return $this;
    }

    public function removeCategory(Category $category) {
        $this->categories->removeElement($category);
        return $this;
    }

    public function removeCommentary(Commentary $commentary) {
        $this->commentaries->removeElement($commentary);
        return $this;
    }

    public function removeNote(Note $note) {
        $this->notes->removeElement($note);
        return $this;
    }
}
