<?php

namespace App\Entity;

use App\Repository\ChatonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatonRepository::class)]
class Chaton
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?bool $Sterilise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Photo = null;

    #[ORM\ManyToOne(inversedBy: 'chatons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $Categorie = null;

    #[ORM\ManyToMany(targetEntity: Proprietaire::class, inversedBy: 'chatons')]
    private Collection $proprietaire_id;

    public function __construct()
    {
        $this->proprietaire_id = new ArrayCollection();
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

    public function isSterilise(): ?bool
    {
        return $this->Sterilise;
    }

    public function setSterilise(bool $Sterilise): self
    {
        $this->Sterilise = $Sterilise;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(?string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    /**
     * @return Collection<int, Proprietaire>
     */
    public function getProprietaireId(): Collection
    {
        return $this->proprietaire_id;
    }

    public function addProprietaireId(Proprietaire $proprietaireId): self
    {
        if (!$this->proprietaire_id->contains($proprietaireId)) {
            $this->proprietaire_id->add($proprietaireId);
        }

        return $this;
    }

    public function removeProprietaireId(Proprietaire $proprietaireId): self
    {
        $this->proprietaire_id->removeElement($proprietaireId);

        return $this;
    }
}
