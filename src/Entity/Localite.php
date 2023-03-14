<?php

namespace App\Entity;

use App\Repository\LocaliteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Cache\Region;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocaliteRepository::class)]
class Localite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $region = null;

    #[ORM\Column(length: 40)]
    private ?string $ville = null;

    #[ORM\Column(length: 50)]
    private ?string $quartier = null;

    #[ORM\OneToMany(mappedBy: 'localite', targetEntity: Maison::class)]
    private Collection $maisons;

    #[ORM\OneToMany(mappedBy: 'localite', targetEntity: Immeuble::class)]
    private Collection $immeubles;

    #[ORM\OneToMany(mappedBy: 'localite', targetEntity: Terrain::class)]
    private Collection $terrains;



    public function __construct()
    {
        $this->maisons = new ArrayCollection();
        $this->immeubles = new ArrayCollection();
        $this->terrains = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getQuartier(): ?string
    {
        return $this->quartier;
    }

    public function setQuartier(string $quartier): self
    {
        $this->quartier = $quartier;

        return $this;
    }


    public function __toString()
    {
        return $this->region . " / " . $this->ville . " / " . $this->quartier;
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
            $maison->setLocalite($this);
        }

        return $this;
    }

    public function removeMaison(Maison $maison): self
    {
        if ($this->maisons->removeElement($maison)) {
            // set the owning side to null (unless already changed)
            if ($maison->getLocalite() === $this) {
                $maison->setLocalite(null);
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
            $immeuble->setLocalite($this);
        }

        return $this;
    }

    public function removeImmeuble(Immeuble $immeuble): self
    {
        if ($this->immeubles->removeElement($immeuble)) {
            // set the owning side to null (unless already changed)
            if ($immeuble->getLocalite() === $this) {
                $immeuble->setLocalite(null);
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
            $terrain->setLocalite($this);
        }

        return $this;
    }

    public function removeTerrain(Terrain $terrain): self
    {
        if ($this->terrains->removeElement($terrain)) {
            // set the owning side to null (unless already changed)
            if ($terrain->getLocalite() === $this) {
                $terrain->setLocalite(null);
            }
        }

        return $this;
    }
}