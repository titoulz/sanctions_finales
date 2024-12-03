<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'promotions')]
class Promotion {
    #[ORM\Id]
    #[ORM\Column(name:'id', type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(name: 'libelle', type: 'string', length: 100)]
    protected string $libelle;

    #[ORM\Column(name: 'annee', type: 'string', length: 4)]
    protected string $annee;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getAnnee(): string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): void
    {
        $this->annee = $annee;
    }
}