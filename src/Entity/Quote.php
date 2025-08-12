<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'quote')]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Lead::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Lead $lead;

    #[ORM\Column(type: 'float')]
    private float $totalKwP; // puissance crête

    #[ORM\Column(type: 'float')]
    private float $estimatedProductionKwhYear;

    #[ORM\Column(type: 'float')]
    private float $netPrice; // € TTC

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $items = null; // [{productId, qty, unitPrice}]

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = 'draft'; // draft|sent|accepted|rejected

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct() { $this->createdAt = new \DateTimeImmutable(); }
}