<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Appartement $appartements = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Immeuble $immeubles = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Maison $maisons = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Terrain $terrains = null;


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

    public function getAppartements(): ?Appartement
    {
        return $this->appartements;
    }

    public function setAppartements(?Appartement $appartements): self
    {
        $this->appartements = $appartements;

        return $this;
    }

    public function getImmeubles(): ?Immeuble
    {
        return $this->immeubles;
    }

    public function setImmeubles(?Immeuble $immeubles): self
    {
        $this->immeubles = $immeubles;

        return $this;
    }

    public function getMaisons(): ?Maison
    {
        return $this->maisons;
    }

    public function setMaisons(?Maison $maisons): self
    {
        $this->maisons = $maisons;

        return $this;
    }

    public function getTerrains(): ?Terrain
    {
        return $this->terrains;
    }

    public function setTerrains(?Terrain $terrains): self
    {
        $this->terrains = $terrains;

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }
}