<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $decription;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $groupProduct;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $IMG;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: comment::class, orphanRemoval: true)]
    private $comments;

    #[ORM\ManyToMany(targetEntity: caracteristiques::class, inversedBy: 'products')]
    private $caracteristiques;

    #[ORM\ManyToMany(targetEntity: panierContent::class, inversedBy: 'products')]
    private $panierContent;

    #[ORM\ManyToMany(targetEntity: commandeContent::class, inversedBy: 'products')]
    private $commandeContent;

    #[ORM\ManyToOne(targetEntity: fournisseur::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $fournisseur;

    #[ORM\ManyToMany(targetEntity: category::class, inversedBy: 'products')]
    private $category;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->caracteristiques = new ArrayCollection();
        $this->panierContent = new ArrayCollection();
        $this->commandeContent = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(string $decription): self
    {
        $this->decription = $decription;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getGroupProduct(): ?string
    {
        return $this->groupProduct;
    }

    public function setGroupProduct(string $groupProduct): self
    {
        $this->groupProduct = $groupProduct;

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

    public function getIMG(): ?string
    {
        return $this->IMG;
    }

    public function setIMG(?string $IMG): self
    {
        $this->IMG = $IMG;

        return $this;
    }

    /**
     * @return Collection<int, comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, caracteristiques>
     */
    public function getCaracteristiques(): Collection
    {
        return $this->caracteristiques;
    }

    public function addCaracteristique(caracteristiques $caracteristique): self
    {
        if (!$this->caracteristiques->contains($caracteristique)) {
            $this->caracteristiques[] = $caracteristique;
        }

        return $this;
    }

    public function removeCaracteristique(caracteristiques $caracteristique): self
    {
        $this->caracteristiques->removeElement($caracteristique);

        return $this;
    }

    /**
     * @return Collection<int, panierContent>
     */
    public function getPanierContent(): Collection
    {
        return $this->panierContent;
    }

    public function addPanierContent(panierContent $panierContent): self
    {
        if (!$this->panierContent->contains($panierContent)) {
            $this->panierContent[] = $panierContent;
        }

        return $this;
    }

    public function removePanierContent(panierContent $panierContent): self
    {
        $this->panierContent->removeElement($panierContent);

        return $this;
    }

    /**
     * @return Collection<int, commandeContent>
     */
    public function getCommandeContent(): Collection
    {
        return $this->commandeContent;
    }

    public function addCommandeContent(commandeContent $commandeContent): self
    {
        if (!$this->commandeContent->contains($commandeContent)) {
            $this->commandeContent[] = $commandeContent;
        }

        return $this;
    }

    public function removeCommandeContent(commandeContent $commandeContent): self
    {
        $this->commandeContent->removeElement($commandeContent);

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

    /**
     * @return Collection<int, category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
