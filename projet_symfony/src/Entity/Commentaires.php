<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $note;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'commentaire')]
    #[ORM\JoinColumn(nullable: false)]
    private $produit;

    #[ORM\ManyToOne(targetEntity: Clients::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private $auteur;

    #[ORM\Column(type: 'boolean')]
    private $isApprouved;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getAuteur(): ?Clients
    {
        return $this->auteur;
    }

    public function setAuteur(?Clients $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function isIsApprouved(): ?bool
    {
        return $this->isApprouved;
    }

    public function setIsApprouved(bool $isApprouved): self
    {
        $this->isApprouved = $isApprouved;

        return $this;
    }
}
