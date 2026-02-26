<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'roles')]
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

    public function getName() : string 
    {
        return $this->name;
    }

    public function setName(string $newName) : void 
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
}

