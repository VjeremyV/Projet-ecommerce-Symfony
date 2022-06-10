<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(mappedBy: 'panier', targetEntity: Customers::class, cascade: ['persist', 'remove'])]
    private $customers;

    #[ORM\OneToMany(mappedBy: 'panier', targetEntity: panierContent::class, orphanRemoval: true)]
    private $content;

    public function __construct()
    {
        $this->content = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomers(): ?Customers
    {
        return $this->customers;
    }

    public function setCustomers(?Customers $customers): self
    {
        // unset the owning side of the relation if necessary
        if ($customers === null && $this->customers !== null) {
            $this->customers->setPanier(null);
        }

        // set the owning side of the relation if necessary
        if ($customers !== null && $customers->getPanier() !== $this) {
            $customers->setPanier($this);
        }

        $this->customers = $customers;

        return $this;
    }

    /**
     * @return Collection<int, panierContent>
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(panierContent $content): self
    {
        if (!$this->content->contains($content)) {
            $this->content[] = $content;
            $content->setPanier($this);
        }

        return $this;
    }

    public function removeContent(panierContent $content): self
    {
        if ($this->content->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getPanier() === $this) {
                $content->setPanier(null);
            }
        }

        return $this;
    }
}
