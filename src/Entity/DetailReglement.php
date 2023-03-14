<?php

namespace App\Entity;

use App\Repository\DetailReglementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailReglementRepository::class)]
class DetailReglement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $mois = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $annee = null;

    #[ORM\ManyToOne(inversedBy: 'detailReglements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reglement $reglement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getReglement(): ?Reglement
    {
        return $this->reglement;
    }

    public function setReglement(?Reglement $reglement): self
    {
        $this->reglement = $reglement;

        return $this;
    }
    public function __toString()
    {
        return $this->mois . " / " . $this->annee;
    }
}