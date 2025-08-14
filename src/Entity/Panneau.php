<?php

namespace App\Entity;

use App\Repository\PanneauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanneauRepository::class)]
class Panneau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $Panneau = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPanneau(): ?string
    {
        return $this->Panneau;
    }

    public function setPanneau(string $Panneau): static
    {
        $this->Panneau = $Panneau;

        return $this;
    }
}
