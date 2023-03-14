<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $typeOffre = null;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Maison::class)]
    private Collection $maisons;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Terrain::class)]
    private Collection $terrains;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Immeuble::class)]
    private Collection $immeubles;



    public function __construct()
    {
        $this->maisons = new ArrayCollection();
        $this->terrains = new ArrayCollection();
        $this->immeubles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeOffre(): ?string
    {
        return $this->typeOffre;
    }

    public function setTypeOffre(string $typeOffre): self
    {
        $this->typeOffre = $typeOffre;

        return $this;
    }


    public function __toString()
    {
        return $this->typeOffre;
    }

    /**
     * @return Collection<int, Maison>
     */
    public function getMaisons(): Collection
    {
        return $this->maisons;
    }

    public function addMaison(Maison $maison): self
    {
        if (!$this->maisons->contains($maison)) {
            $this->maisons->add($maison);
            $maison->setOffre($this);
        }

        return $this;
    }

    public function removeMaison(Maison $maison): self
    {
        if ($this->maisons->removeElement($maison)) {
            // set the owning side to null (unless already changed)
            if ($maison->getOffre() === $this) {
                $maison->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Terrain>
     */
    public function getTerrains(): Collection
    {
        return $this->terrains;
    }

    public function addTerrain(Terrain $terrain): self
    {
        if (!$this->terrains->contains($terrain)) {
            $this->terrains->add($terrain);
            $terrain->setOffre($this);
        }

        return $this;
    }

    public function removeTerrain(Terrain $terrain): self
    {
        if ($this->terrains->removeElement($terrain)) {
            // set the owning side to null (unless already changed)
            if ($terrain->getOffre() === $this) {
                $terrain->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Immeuble>
     */
    public function getImmeubles(): Collection
    {
        return $this->immeubles;
    }

    public function addImmeuble(Immeuble $immeuble): self
    {
        if (!$this->immeubles->contains($immeuble)) {
            $this->immeubles->add($immeuble);
            $immeuble->setOffre($this);
        }

        return $this;
    }

    public function removeImmeuble(Immeuble $immeuble): self
    {
        if ($this->immeubles->removeElement($immeuble)) {
            // set the owning side to null (unless already changed)
            if ($immeuble->getOffre() === $this) {
                $immeuble->setOffre(null);
            }
        }

        return $this;
    }
}