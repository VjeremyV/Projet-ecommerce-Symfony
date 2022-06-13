<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class)]
    private $produits;

    #[ORM\ManyToMany(targetEntity: TypeCaracteristiques::class, inversedBy: 'categories')]
    private $typeCaracteristique;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->typeCaracteristique = new ArrayCollection();
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
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, typeCaracteristiques>
     */
    public function getTypeCaracteristique(): Collection
    {
        return $this->typeCaracteristique;
    }

    public function addTypeCaracteristique(typeCaracteristiques $typeCaracteristique): self
    {
        if (!$this->typeCaracteristique->contains($typeCaracteristique)) {
            $this->typeCaracteristique[] = $typeCaracteristique;
        }

        return $this;
    }

    public function removeTypeCaracteristique(typeCaracteristiques $typeCaracteristique): self
    {
        $this->typeCaracteristique->removeElement($typeCaracteristique);

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
