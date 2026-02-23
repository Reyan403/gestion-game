<?php

namespace App\Entity;

use App\Repository\CommentaryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: CommentaryRepository::class)]
class Commentary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    // ManyToOne: si une relation OneToMany et ManyToOne émerge, alors celui qui a la clef étrangère porte ManyToOne
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaries')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'commentaries')]
    #[ORM\JoinColumn(nullable: false)]
    private Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?int
    {
        return $this->description;
    }

    public function setDescription(string $newDescription) : void 
    {
        $this->description = $newDescription;
    }
}
