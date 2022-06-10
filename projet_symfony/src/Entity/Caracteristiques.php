<?php

namespace App\Entity;

use App\Repository\CaracteristiquesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaracteristiquesRepository::class)]
class Caracteristiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\ManyToOne(targetEntity: TypeCaracteristiques::class, inversedBy: 'caracteristiques')]
    #[ORM\JoinColumn(nullable: false)]
    private $typeCaracteristiques;

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

    public function getTypeCaracteristiques(): ?TypeCaracteristiques
    {
        return $this->typeCaracteristiques;
    }

    public function setTypeCaracteristiques(?TypeCaracteristiques $typeCaracteristiques): self
    {
        $this->typeCaracteristiques = $typeCaracteristiques;

        return $this;
    }
}
