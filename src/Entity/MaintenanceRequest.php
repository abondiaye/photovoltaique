<?php
namespace App\Entity;

use App\Entity\Lead;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MaintenanceRequest
{
    #[ORM\Id] #[ORM\GeneratedValue] #[ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Lead::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Lead $lead;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $requestedAt; // date choisie sur le calendrier

    #[ORM\Column(length: 20)]
    private string $type = 'cleaning'; // cleaning|inspection|repair

    #[ORM\Column(length: 20)]
    private string $status = 'pending'; // pending|approved|rejected|done

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $photos = null; // on stockera des chemins/URLs

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct() { $this->createdAt = new \DateTimeImmutable(); }

    // getters/setters ...
}
