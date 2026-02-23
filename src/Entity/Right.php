<?php

namespace App\Entity;

use App\Repository\RightRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RightRepository::class)]
#[ORM\Table(name: '`right`')]
class Right
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'rights')]
    private Collection $roles;

     public function __construct()
    {
        $this->roles = newArrayCollection();
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
