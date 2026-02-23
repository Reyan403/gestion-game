<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $game = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $user = null;

    #[ORM\Column]
    private ?int $note_game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdGame(): ?int
    {
        return $this->id_game;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function getNoteGame(): ?int
    {
        return $this->note_game;
    }

    public function setNoteGame(int $newNoteGame): void
    {
        $this->note_game = $newNoteGame;
    }
}
