<?php

namespace App\Entity;

use App\Repository\AppartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppartementRepository::class)]
class Appartement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $Etage = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbrePiece = null;

    #[ORM\Column]
    private ?bool $disponibilite = null;

    #[ORM\ManyToOne(inversedBy: 'appartements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Immeuble $immeuble = null;

    #[ORM\OneToMany(mappedBy: 'appartement', targetEntity: Contrat::class)]
    private Collection $contrats;

    #[ORM\OneToMany(mappedBy: 'appartements', targetEntity: Image::class, orphanRemoval: true, cascade: ["persist"])]
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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->Etage;
    }

    public function setEtage(int $Etage): self
    {
        $this->Etage = $Etage;

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

    public function getImmeuble(): ?Immeuble
    {
        return $this->immeuble;
    }

    public function setImmeuble(?Immeuble $immeuble): self
    {
        $this->immeuble = $immeuble;

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
            $contrat->setAppartement($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getAppartement() === $this) {
                $contrat->setAppartement(null);
            }
        }

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
    public function __toString()
    {
        return "Immeuble ".$this->getImmeuble(). " / Appartement ".$this->numero." (".$this->getImmeuble()->getOffre()->getTypeOffre().")" ;
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
            $image->setAppartements($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAppartements() === $this) {
                $image->setAppartements(null);
            }
        }

        return $this->get;
    }
}