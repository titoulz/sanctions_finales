<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'sanctions')]
class Sanction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $eleve;

    #[ORM\Column(type: 'string')]
    private string $professeur;

    #[ORM\Column(type: 'string')]
    private string $motif;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $date_Incident;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $date_Creation;

    #[ORM\Column(type: 'integer')]
    private int $cree_Par;

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getEleve(): string { return $this->eleve; }
    public function getProfesseur(): string { return $this->professeur; }
    public function getMotif(): string { return $this->motif; }
    public function getDescription(): string { return $this->description; }
    public function getDateIncident(): \DateTime { return $this->date_Incident; }
    public function getDateCreation(): \DateTime { return $this->date_Creation; }
    public function getCreePar(): int { return $this->cree_Par; }

    // Setters
    public function setEleve(string $eleve): self { $this->eleve = $eleve; return $this; }
    public function setProfesseur(string $professeur): self { $this->professeur = $professeur; return $this; }
    public function setMotif(string $motif): self { $this->motif = $motif; return $this; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }
    public function setDateIncident(\DateTime $date_Incident): self { $this->date_Incident = $date_Incident; return $this; }
    public function setDateCreation(\DateTime $date_Creation): self { $this->date_Creation = $date_Creation; return $this; }
    public function setCreePar(int $cree_Par): self { $this->cree_Par = $cree_Par; return $this; }
}