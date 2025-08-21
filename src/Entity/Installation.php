<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Quote;

#[ORM\Entity]
#[ORM\Table(name: 'installation')]
class Installation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Quote::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Quote $quote = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $scheduledAt;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = 'planned'; // planned|in_progress|done|paused

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $photos = null; // URLs
}
