<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'text')]
    private $description;

   

    #[ORM\Column(type: 'float')]
    private $prix;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'boolean')]
    private $is_active;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Commentaires::class, orphanRemoval: true)]
    private $commentaire;

    #[ORM\ManyToOne(targetEntity: Fournisseur::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private $fournisseur;

    #[ORM\ManyToOne(targetEntity: Categories::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private $categorie;

    #[ORM\ManyToMany(targetEntity: Caracteristiques::class, inversedBy: 'produits')]
    private $caracteristiques;

    #[ORM\OneToMany(mappedBy: 'produits', targetEntity: Contenu::class)]
    private $contenus;

    #[ORM\ManyToMany(targetEntity: GroupProduit::class, inversedBy: 'produits')]
    private $GroupProduit;

 

    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
        $this->caracteristiques = new ArrayCollection();
        $this->contenus = new ArrayCollection();
        $this->GroupProduit = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

   

    /**
     * @return Collection<int, commentaires>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(commentaires $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setProduit($this);
        }

        return $this;
    }

    public function removeCommentaire(commentaires $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getProduit() === $this) {
                $commentaire->setProduit(null);
            }
        }

        return $this;
    }

    public function getFournisseur(): ?fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getCategorie(): ?categories
    {
        return $this->categorie;
    }

    public function setCategorie(?categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


    /**
     * @return Collection<int, Caracteristiques>
     */
    public function getCaracteristiques(): Collection
    {
        return $this->caracteristiques;
    }

    public function addCaracteristique(Caracteristiques $caracteristique): self
    {
        if (!$this->caracteristiques->contains($caracteristique)) {
            $this->caracteristiques[] = $caracteristique;
        }

        return $this;
    }

    public function removeCaracteristique(Caracteristiques $caracteristique): self
    {
        $this->caracteristiques->removeElement($caracteristique);

        return $this;
    }

    /**
     * @return Collection<int, Contenu>
     */
    public function getContenus(): Collection
    {
        return $this->contenus;
    }

    public function addContenu(Contenu $contenu): self
    {
        if (!$this->contenus->contains($contenu)) {
            $this->contenus[] = $contenu;
            $contenu->setProduits($this);
        }

        return $this;
    }

    public function removeContenu(Contenu $contenu): self
    {
        if ($this->contenus->removeElement($contenu)) {
            // set the owning side to null (unless already changed)
            if ($contenu->getProduits() === $this) {
                $contenu->setProduits(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection<int, GroupProduit>
     */
    public function getGroupProduit(): Collection
    {
        return $this->GroupProduit;
    }

    public function addGroupProduit(GroupProduit $groupProduit): self
    {
        if (!$this->GroupProduit->contains($groupProduit)) {
            $this->GroupProduit[] = $groupProduit;
        }

        return $this;
    }

    public function removeGroupProduit(GroupProduit $groupProduit): self
    {
        $this->GroupProduit->removeElement($groupProduit);

        return $this;
    }

    
}
