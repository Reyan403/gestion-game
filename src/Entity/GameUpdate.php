<?php

namespace App\Entity;

use App\Repository\GameUpdateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameUpdateRepository::class)]
class GameUpdate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column]
    private string $image;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'gamesUpdate')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'gamesUpdate')]
    private Game $game;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'gamesUpdate')]
    private Collection $categoriesUpdate;

    public function __construct()
    {
        $this->categoriesUpdate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setTitle(string $newTitle): void
    {
        $this->title = $newTitle;
    }

    public function setDescription(string $newDescription): void
    {
        $this->description = $newDescription;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $newImage): void
    {
        $this->image = $newImage;
    }

    public function getDateUpdated(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setDateUpdated(?\DateTime $newDateUpdated): void
    {
        $this->updatedAt = $newDateUpdated;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $newUser): void
    {
        $this->user = $newUser;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    // Méthodes des collection
    public function getCategories(): Collection
    {
        return $this->categoriesUpdate;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categoriesUpdate->contains($category)) {
            $this->categoriesUpdate->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category)
    {
        $this->categoriesUpdate->removeElement($category);

        return $this;
    }
}
