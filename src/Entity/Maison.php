<?php

namespace App\Entity;

use App\Repository\MaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaisonRepository::class)]
class Maison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $superficie = null;

    #[ORM\Column(length: 5)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbrePiece = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbreEtage = null;

    #[ORM\OneToMany(mappedBy: 'maison', targetEntity: Contrat::class)]
    private Collection $contrats;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $disponibilite = null;

    #[ORM\ManyToOne(inversedBy: 'maisons')]
    private ?Offre $offre = null;

    #[ORM\ManyToOne(inversedBy: 'maisons')]
    private ?Localite $localite = null;

    #[ORM\OneToMany(mappedBy: 'maisons', targetEntity: Image::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $images;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(float $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNbrePiece(): ?int
    {
        return $this->nbrePiece;
    }

    public function setNbrePiece(int $nbrePiece): self
    {
        $this->nbrePiece = $nbrePiece;

        return $this;
    }

    public function getNbreEtage(): ?int
    {
        return $this->nbreEtage;
    }

    public function setNbreEtage(int $nbreEtage): self
    {
        $this->nbreEtage = $nbreEtage;

        return $this;
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setMaison($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getMaison() === $this) {
                $contrat->setMaison(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getLocalite(): ?Localite
    {
        return $this->localite;
    }

    public function setLocalite(?Localite $localite): self
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setMaisons($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getMaisons() === $this) {
                $image->setMaisons(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return "Maison ".$this->numero . " / " . $this->nbreEtage. " etage(s) / " . $this->superficie . " m² "." (".$this->getOffre()->getTypeOffre().")";
    }
}