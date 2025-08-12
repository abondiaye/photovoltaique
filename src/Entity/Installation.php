<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'installation')]
class Installation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Quote::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'SET NULL')]
    private Quote $quote;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $scheduledAt;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = 'planned'; // planned|in_progress|done|paused

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $photos = null; // URLs
}