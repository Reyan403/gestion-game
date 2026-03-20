<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['mail'], message: 'Il existe déjà un compte avec cet email')]
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

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'user')]
    private Collection $games;

    #[ORM\OneToMany(targetEntity: GameUpdate::class, mappedBy: 'user')]
    private Collection $gamesUpdate;

    // mappedBy : celui qui n'a pas la clef étrangère dans sa table
    #[ORM\OneToMany(targetEntity: Commentary::class, mappedBy: 'user')]
    private Collection $commentaries;

    // inversedBy : on va se poser la question : "Et si l'utilisateur n'a le droit que d'un seul rôle ?".
    // Alors il va prendre la clef étrangère dans sa table
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $rolesEntities;

    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'user')]
    private Collection $notes;

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'isValidatedBy')]
    private Collection $gameValidatedBy;

    public function __construct()
    {
        $this->rolesEntities = new ArrayCollection();
        $this->commentaries = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->gamesUpdate = new ArrayCollection();
        $this->gameValidatedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function setName(?string $newName): void
    {
        $this->name = $newName;
    }

    public function setMail(?string $newMail): void
    {
        $this->mail = $newMail;
    }

    public function setPassword(string $newPassword): void
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

        foreach ($this->rolesEntities as $role) {
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
        return $this->rolesEntities;
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
        return $this->games;
    }

    public function getGameUpdate(): Collection
    {
        return $this->gamesUpdate;
    }

    public function getGameValidatedBy(): Collection
    {
        return $this->gameValidatedBy;
    }

    public function addRole(Role $role): self
    {
        if (!$this->rolesEntities->contains($role)) {
            $this->rolesEntities->add($role);
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

    public function addGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->add($game);
        }

        return $this;
    }

    public function addGameUpdate(GameUpdate $gameUpdate): self
    {
        if ($this->gamesUpdate->contains($gameUpdate)) {
            $this->gamesUpdate->add($gameUpdate);
        }

        return $this;
    }

    public function addGameValidatedBy(Game $newGameValidatedBy): self
    {
        if ($this->gameValidatedBy->contains($newGameValidatedBy)) {
            $this->gameValidatedBy->add($newGameValidatedBy);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->rolesEntities->removeElement($role);

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

    public function removeGame(Game $game): self
    {
        $this->games->removeElement($game);

        return $this;
    }

    public function removeGameUpdate(GameUpdate $gameUpdate): self
    {
        $this->gamesUpdate->removeElement($gameUpdate);

        return $this;
    }

    public function removeGameValidatedBy(Game $newGameValidatedBy): self
    {
        $this->gameValidatedBy->removeElement($newGameValidatedBy);

        return $this;
    }
}
