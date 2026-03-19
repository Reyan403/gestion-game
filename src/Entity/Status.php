<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\OneToMany(targetEntity: GameUpdate::class, mappedBy: 'status')]
    private Collection $gamesUpdate;

    public function __construct() 
    {
        $this->gamesUpdate = new ArrayCollection();
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

    public function getGameUpdate(): Collection
    {
        return $this->gamesUpdate;
    }

    public function addCategory(GameUpdate $gameUpdate): self 
    {
        if (!$this->gamesUpdate->contains($gameUpdate)) {
            $this->gamesUpdate->add($gameUpdate);
        }

        return $this;
    }

    public function removeNote(GameUpdate $gameUpdate) {
        $this->gamesUpdate->removeElement($gameUpdate);

        return $this;
    }
}
