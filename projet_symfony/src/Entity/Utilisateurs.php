<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $pseudo;

    #[ORM\Column(type: 'string', length: 255)]
    private $motDePasse;

    #[ORM\Column(type: 'string', length: 255)]
    private $role;

    #[ORM\OneToOne(inversedBy: 'utilisateurs', targetEntity: clients::class, cascade: ['persist', 'remove'])]
    private $client;

    #[ORM\OneToMany(mappedBy: 'utilisateurs', targetEntity: commandes::class)]
    private $commande;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getClient(): ?clients
    {
        return $this->client;
    }

    public function setClient(?clients $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, commandes>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(commandes $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setUtilisateurs($this);
        }

        return $this;
    }

    public function removeCommande(commandes $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateurs() === $this) {
                $commande->setUtilisateurs(null);
            }
        }

        return $this;
    }
}
