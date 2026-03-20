<?php

namespace App\Entity;

use App\Repository\CommentaryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaryRepository::class)]
class Commentary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column]
    private bool $isValidated;

    #[ORM\Column]
    private bool $isArchived;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    // ManyToOne: si une relation OneToMany et ManyToOne émerge, alors celui qui a la clef étrangère porte ManyToOne
    // onDelete: 'CASCADE', sert à faire en sorte que si on supprimer, par exemple dans ce cas, un utilisateur, ça supprime toute la ligne où il contient l'id de cet utilisateur
    // dans l'entité commentary, pour tout simplement éviter une erreur
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaries')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'commentaries')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $newDescription): void
    {
        $this->description = $newDescription;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getDate(): \DateTime
    {
        return $this->createdAt;
    }

    public function setDate(\DateTime $newDate): void
    {
        $this->createdAt = $newDate;
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
}
