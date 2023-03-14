<?php

namespace App\Entity;

use App\Repository\ImmeubleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImmeubleRepository::class)]
class Immeuble
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbreEtage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateConstruction = null;

    #[ORM\OneToMany(mappedBy: 'immeuble', targetEntity: Appartement::class, orphanRemoval: true)]
    private Collection $appartements;

    #[ORM\Column(length: 255)]
    private ?string $nomImmeuble = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $disponibilite = null;

    #[ORM\ManyToOne(inversedBy: 'immeubles')]
    private ?Offre $offre = null;

    #[ORM\ManyToOne(inversedBy: 'immeubles')]
    private ?Localite $localite = null;

    #[ORM\OneToMany(mappedBy: 'immeubles', targetEntity: Image::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $images;

    public function __construct()
    {
        $this->appartements = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateConstruction(): ?\DateTimeInterface
    {
        return $this->dateConstruction;
    }

    public function setDateConstruction(\DateTimeInterface $dateConstruction): self
    {
        $this->dateConstruction = $dateConstruction;

        return $this;
    }

    /**
     * @return Collection<int, Appartement>
     */
    public function getAppartements(): Collection
    {
        return $this->appartements;
    }

    public function addAppartement(Appartement $appartement): self
    {
        if (!$this->appartements->contains($appartement)) {
            $this->appartements->add($appartement);
            $appartement->setImmeuble($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): self
    {
        if ($this->appartements->removeElement($appartement)) {
            // set the owning side to null (unless already changed)
            if ($appartement->getImmeuble() === $this) {
                $appartement->setImmeuble(null);
            }
        }

        return $this;
    }

    public function getNomImmeuble(): ?string
    {
        return $this->nomImmeuble;
    }

    public function setNomImmeuble(string $nomImmeuble): self
    {
        $this->nomImmeuble = $nomImmeuble;

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
            $image->setImmeubles($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getImmeubles() === $this) {
                $image->setImmeubles(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return "Immeuble ".$this->nomImmeuble;
    }
}