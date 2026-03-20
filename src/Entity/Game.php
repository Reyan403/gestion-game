<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column]
    private string $image;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $whenIsValidated = null;

    #[ORM\Column]
    private bool $isValidated;

    #[ORM\Column]
    private bool $isArchived;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'gameValidatedBy')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $isValidatedBy = null;

    #[ORM\OneToMany(targetEntity: Commentary::class, mappedBy: 'game')]
    private Collection $commentaries;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'games')]
    private Collection $categories;

    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'game')]
    private Collection $notes;

    #[ORM\OneToMany(targetEntity: GameUpdate::class, mappedBy: 'game')]
    private Collection $gamesUpdate;

    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->gamesUpdate = new ArrayCollection();
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

    public function isValidated(): bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $newIsValidated): void
    {
        $this->isValidated = $newIsValidated;
    }

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $newIsArchived): void
    {
        $this->isArchived = $newIsArchived;
    }

    public function getDateCreated(): \DateTime
    {
        return $this->createdAt;
    }

    public function setDateCreated(\DateTime $newDateCreated): void
    {
        $this->createdAt = $newDateCreated;
    }

    public function getDateUpdated(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setDateUpdated(?\DateTime $newDateUpdated): void
    {
        $this->updatedAt = $newDateUpdated;
    }

    public function getWhenIsValidated(): ?\DateTime
    {
        return $this->whenIsValidated;
    }

    public function setWhenIsValidated(?\DateTime $newWhenIsValidated): void
    {
        $this->whenIsValidated = $newWhenIsValidated;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $newUser): void
    {
        $this->user = $newUser;
    }

    public function getIsValidatedBy(): ?User
    {
        return $this->isValidatedBy;
    }

    public function setIsValidatedBy(?User $newIsValidatedBy): void
    {
        $this->isValidatedBy = $newIsValidatedBy;
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

    public function getGame(): Collection
    {
        return $this->gamesUpdate;
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

    public function addGame(GameUpdate $gameUpdate): self
    {
        if (!$this->gamesUpdate->contains($gameUpdate)) {
            $this->gamesUpdate->add($gameUpdate);
        }

        return $this;
    }

    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function removeCommentary(Commentary $commentary)
    {
        $this->commentaries->removeElement($commentary);

        return $this;
    }

    public function removeNote(Note $note)
    {
        $this->notes->removeElement($note);

        return $this;
    }

    public function removeGame(GameUpdate $gameUpdate)
    {
        $this->gamesUpdate->removeElement($gameUpdate);

        return $this;
    }
}
