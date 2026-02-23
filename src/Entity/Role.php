<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'roles')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Right::class, inversedBy: 'roles')]
    private Collection $rights;

    public function __construct()
    {
        $this->rights = newArrayCollection();
        $this->users = newArrayCollection();
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
}
