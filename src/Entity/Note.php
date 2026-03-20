<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Game $game = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $note_game = null;

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getNoteGame(): ?int
    {
        return $this->note_game;
    }

    public function setNoteGame(int $newNoteGame): void
    {
        $this->note_game = $newNoteGame;
    }

    public function setGame(?Game $game): void
    {
        $this->game = $game;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
