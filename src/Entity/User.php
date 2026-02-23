<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(unique: true, length: 255)]
    private string $mail;

    #[ORM\Column(length: 255)]
    private string $password;

    // mappedBy : celui qui n'a pas la clef étrangère dans sa table
    #[ORM\OneToMany(targetEntity: Commentary::class, mappedBy: 'user')]
    private Collection $commentaries;

    // inversedBy : on va se poser la question : "Et si l'utilisateur n'a le droit que d'un seul rôle ?".
    // Alors il va prendre la clef étrangère dans sa table
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $roles;

    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'user')]
    private Collection $notes;

    public function __construct() 
    {
        $this->roles = newArrayCollection();
        $this->commentaries = newArrayCollection();
        $this->notes = newArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName() : string 
    {
        return $this->name;
    }

    public function getMail() : string 
    {
        return $this->mail;
    }

    public function getPassword() : string 
    {
        return $this->password;
    }

    public function setName(string $newName) : void 
    {
        $this->name = $newName;
    }

    public function setMail(string $newMail) : void
    {
        $this->mail = $newMail;
    }

    public function setPassword(string $newPassword) : void
    {
        $this->mail = password_hash($newPassword, PASSWORD_DEFAULT);
    }
}
