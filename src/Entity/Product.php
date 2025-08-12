<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private string $name;

    #[ORM\Column(length: 50)]
    private string $category; // panel|inverter|battery|mount

    #[ORM\Column(type: 'float')]
    private float $powerW; // pour les panneaux

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $price = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $specs = null; // {efficiency:%, brand:..., warranty:...}
}