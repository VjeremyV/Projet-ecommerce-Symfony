<?php

namespace App\Entity;

use App\Repository\CaracteristiquesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaracteristiquesRepository::class)]
class Caracteristiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\ManyToOne(targetEntity: TypeCaracteristiques::class, inversedBy: 'caracteristiques')]
    #[ORM\JoinColumn(nullable: false)]
    private $typeCaracteristiques;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'caracteristiques')]
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTypeCaracteristiques(): ?TypeCaracteristiques
    {
        return $this->typeCaracteristiques;
    }

    public function setTypeCaracteristiques(?TypeCaracteristiques $typeCaracteristiques): self
    {
        $this->typeCaracteristiques = $typeCaracteristiques;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->addCaracteristique($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeCaracteristique($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }
}
