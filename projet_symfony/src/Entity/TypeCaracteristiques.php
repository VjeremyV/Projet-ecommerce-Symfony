<?php

namespace App\Entity;

use App\Repository\TypeCaracteristiquesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeCaracteristiquesRepository::class)]
class TypeCaracteristiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'typeCaracteristique')]
    private $categories;

    #[ORM\OneToMany(mappedBy: 'typeCaracteristiques', targetEntity: Caracteristiques::class)]
    private $caracteristiques;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->caracteristiques = new ArrayCollection();
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
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addTypeCaracteristique($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeTypeCaracteristique($this);
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
            $caracteristique->setTypeCaracteristiques($this);
        }

        return $this;
    }

    public function removeCaracteristique(caracteristiques $caracteristique): self
    {
        if ($this->caracteristiques->removeElement($caracteristique)) {
            // set the owning side to null (unless already changed)
            if ($caracteristique->getTypeCaracteristiques() === $this) {
                $caracteristique->setTypeCaracteristiques(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom;
    }
}
