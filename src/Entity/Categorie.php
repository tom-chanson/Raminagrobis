<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Description = null;

    #[ORM\OneToMany(mappedBy: 'Categorie', targetEntity: Chaton::class, orphanRemoval: true)]
    private Collection $chatons;

    public function __construct()
    {
        $this->chatons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, Chaton>
     */
    public function getChatons(): Collection
    {
        return $this->chatons;
    }

    public function addChaton(Chaton $chaton): self
    {
        if (!$this->chatons->contains($chaton)) {
            $this->chatons->add($chaton);
            $chaton->setCategorie($this);
        }

        return $this;
    }

    public function removeChaton(Chaton $chaton): self
    {
        if ($this->chatons->removeElement($chaton)) {
            // set the owning side to null (unless already changed)
            if ($chaton->getCategorie() === $this) {
                $chaton->setCategorie(null);
            }
        }

        return $this;
    }
}
