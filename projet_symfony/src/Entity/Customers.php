<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomersRepository::class)]
class Customers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $surname;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\OneToOne(inversedBy: 'customers', targetEntity: panier::class, cascade: ['persist', 'remove'])]
    private $panier;

    #[ORM\OneToMany(mappedBy: 'customers', targetEntity: commande::class)]
    private $commandes;

    #[ORM\ManyToMany(targetEntity: address::class, inversedBy: 'customers')]
    private $adresse;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->adresse = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPanier(): ?panier
    {
        return $this->panier;
    }

    public function setPanier(?panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    /**
     * @return Collection<int, commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setCustomers($this);
        }

        return $this;
    }

    public function removeCommande(commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getCustomers() === $this) {
                $commande->setCustomers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, address>
     */
    public function getAdresse(): Collection
    {
        return $this->adresse;
    }

    public function addAdresse(address $adresse): self
    {
        if (!$this->adresse->contains($adresse)) {
            $this->adresse[] = $adresse;
        }

        return $this;
    }

    public function removeAdresse(address $adresse): self
    {
        $this->adresse->removeElement($adresse);

        return $this;
    }
}
