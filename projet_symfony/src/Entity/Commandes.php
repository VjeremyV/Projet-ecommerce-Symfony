<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $createdAt;

    #[ORM\Column(type: 'boolean')]
    private $is_panier;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'commande')]
    #[ORM\JoinColumn(nullable: false)]
    private $utilisateurs;

    #[ORM\OneToMany(mappedBy: 'commandes', targetEntity: Contenu::class, orphanRemoval: true)]
    private $contenu;

    public function __construct()
    {
        $this->contenu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isIsPanier(): ?bool
    {
        return $this->is_panier;
    }

    public function setIsPanier(bool $is_panier): self
    {
        $this->is_panier = $is_panier;

        return $this;
    }

    public function getUtilisateurs(): ?Utilisateurs
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateurs $utilisateurs): self
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    /**
     * @return Collection<int, contenu>
     */
    public function getContenu(): Collection
    {
        return $this->contenu;
    }

    public function addContenu(contenu $contenu): self
    {
        if (!$this->contenu->contains($contenu)) {
            $this->contenu[] = $contenu;
            $contenu->setCommandes($this);
        }

        return $this;
    }

    public function removeContenu(contenu $contenu): self
    {
        if ($this->contenu->removeElement($contenu)) {
            // set the owning side to null (unless already changed)
            if ($contenu->getCommandes() === $this) {
                $contenu->setCommandes(null);
            }
        }

        return $this;
    }
}
