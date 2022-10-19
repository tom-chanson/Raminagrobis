<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietaireRepository::class)]
class Proprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column(length: 50)]
    private ?string $Prenom = null;

    #[ORM\ManyToMany(targetEntity: Chaton::class, mappedBy: 'proprietaire_id')]
    private Collection $chatons;

    public function __construct()
    {
        $this->chatons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

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
            $chaton->addProprietaireId($this);
        }

        return $this;
    }

    public function removeChaton(Chaton $chaton): self
    {
        if ($this->chatons->removeElement($chaton)) {
            $chaton->removeProprietaireId($this);
        }

        return $this;
    }
}
