<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Customers::class, inversedBy: 'commandes')]
    private $customers;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: commandeContent::class)]
    private $content;

    public function __construct()
    {
        $this->content = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCustomers(): ?Customers
    {
        return $this->customers;
    }

    public function setCustomers(?Customers $customers): self
    {
        $this->customers = $customers;

        return $this;
    }

    /**
     * @return Collection<int, commandeContent>
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(commandeContent $content): self
    {
        if (!$this->content->contains($content)) {
            $this->content[] = $content;
            $content->setCommande($this);
        }

        return $this;
    }

    public function removeContent(commandeContent $content): self
    {
        if ($this->content->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getCommande() === $this) {
                $content->setCommande(null);
            }
        }

        return $this;
    }
}
