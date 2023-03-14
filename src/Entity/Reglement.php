<?php

namespace App\Entity;

use App\Repository\ReglementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReglementRepository::class)]
class Reglement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateReglement = null;

    #[ORM\ManyToOne(inversedBy: 'reglements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contrat $contrat = null;

    #[ORM\OneToMany(mappedBy: 'reglement', targetEntity: DetailReglement::class, orphanRemoval: true)]
    private Collection $detailReglements;

    #[ORM\ManyToOne(inversedBy: 'reglements')]
    private ?User $user = null;

    public function __construct()
    {
        $this->detailReglements = new ArrayCollection();
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

    public function getDateReglement(): ?\DateTimeInterface
    {
        return $this->dateReglement;
    }

    public function setDateReglement(\DateTimeInterface $dateReglement): self
    {
        $this->dateReglement = $dateReglement;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * @return Collection<int, DetailReglement>
     */
    public function getDetailReglements(): Collection
    {
        return $this->detailReglements;
    }

    public function addDetailReglement(DetailReglement $detailReglement): self
    {
        if (!$this->detailReglements->contains($detailReglement)) {
            $this->detailReglements->add($detailReglement);
            $detailReglement->setReglement($this);
        }

        return $this;
    }

    public function removeDetailReglement(DetailReglement $detailReglement): self
    {
        if ($this->detailReglements->removeElement($detailReglement)) {
            // set the owning side to null (unless already changed)
            if ($detailReglement->getReglement() === $this) {
                $detailReglement->setReglement(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __toString()
    {
        return $this->numero . " / " . $this->getContrat(). " / " . $this->dateReglement;
    }
}