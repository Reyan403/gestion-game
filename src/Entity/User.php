<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['mail'], message: 'There is already an account with this mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(unique: true, length: 255)]
    private ?string $mail = null;

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
        $this->roles = new ArrayCollection();
        $this->commentaries = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName() : ?string 
    {
        return $this->name;
    }

    public function getMail() : ?string 
    {
        return $this->mail;
    }

    public function getPassword() : string 
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id; 
    }

    public function setName(?string $newName) : void 
    {
        $this->name = $newName;
    }

    public function setMail(?string $newMail) : void
    {
        $this->mail = $newMail;
    }

    public function setPassword(string $newPassword) : void
    {
        $this->password = $newPassword;
    }

    /**
         * Retourne les rôles de l'utilisateur sous forme de chaînes de caractères.
         *
         * Cette méthode est utilisée par Symfony Security pour gérer
         * l'autorisation et vérifier les permissions (is_granted, ROLE_ADMIN, etc.).
         * Elle doit obligatoirement retourner un array de strings, même si
         * tu utilises une entité Role pour stocker les rôles en base.
     *
     * Exemple de sortie : ['ROLE_USER', 'ROLE_ADMIN']
     */
    public function getRoles(): array
    {
        $roles = [];

        foreach ($this->roles as $role) {
            $roles[] = $role->getSymfonyRole(); // ex: "ROLE_ADMIN", "ROLE_MODERATOR"
        }

        return array_unique($roles);
    }

    /**
         * Retourne les rôles de l'utilisateur sous forme de Collection d'objets Role.
         *
         * Cette méthode est utilisée pour manipuler la collection d'entités
         * dans le formulaire (EntityType) ou pour toute logique côté Doctrine.
         * Elle ne sert pas directement à Symfony Security, qui ne lit que getRoles().
         *
         * Exemple de sortie : Collection d'objets Role
    */
    public function getRolesEntities(): Collection 
    {
        return $this->roles;
    }

    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addRole(Role $role): self 
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function addCommentary(Commentary $commentary): self 
    {
        if ($this->commentaries->contains($commentary)) {
            $this->commentaries->add($commentary);
        }

        return $this;
    }

    public function addNote(Note $note): self 
    {
        if ($this->notes->contains($note)) {
            $this->notes->add($note);
        }

        return $this;
    }

    public function removeRole(Role $role): self 
    {
        $this->roles->removeElement($role);
        return $this;
    }

    public function removeCommentary(Commentary $commentary): self 
    {
        $this->commentaries->removeElement($commentary);
        return $this;
    }

    public function removeNote(Note $note): self 
    {
        $this->notes->removeElement($note);
        return $this;
    }
}
