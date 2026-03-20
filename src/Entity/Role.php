<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Nom affiché dans l'UI (ex: "Modérateur", "Rédacteur") */
    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    /** Code Symfony Security (ex: "ROLE_MODERATOR", "ROLE_EDITOR") */
    #[ORM\Column(length: 255, unique: true)]
    private string $symfonyRole;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'rolesEntities')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Right::class, inversedBy: 'roles')]
    private Collection $rights;

    public function __construct()
    {
        $this->rights = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $newName): void
    {
        $this->name = $newName;
    }

    public function getSymfonyRole(): string
    {
        return $this->symfonyRole;
    }

    public function setSymfonyRole(string $symfonyRole): void
    {
        $this->symfonyRole = $symfonyRole;
    }

    // Méthodes pour collection
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(Right $right): static
    {
        if (!$this->rights->contains($right)) {
            $this->rights->add($right);
        }

        return $this;
    }

    public function removeRight(Right $right): static
    {
        $this->rights->removeElement($right);

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);

            $user->addRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeRole($this);
        }

        return $this;
    }
}
