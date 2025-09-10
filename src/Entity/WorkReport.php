<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\WorkReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkReportRepository::class)]
#[ORM\Table(name: 'work_report')]
class WorkReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: MaintenanceRequest::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private MaintenanceRequest $request;

    #[ORM\Column(options: ['default' => false])]
    private bool $panelsCleaned = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $structureChecked = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $wiringSecured = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $inverterChecked = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $debrisRemoved = false;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $beforePhotos = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $afterPhotos = [];

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $publishOnSite = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(MaintenanceRequest $request)
    {
        $this->request = $request;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getRequest(): MaintenanceRequest { return $this->request; }
    public function setRequest(MaintenanceRequest $r): self { $this->request = $r; return $this; }

    public function isPanelsCleaned(): bool { return $this->panelsCleaned; }
    public function setPanelsCleaned(bool $v): self { $this->panelsCleaned = $v; return $this; }

    public function isStructureChecked(): bool { return $this->structureChecked; }
    public function setStructureChecked(bool $v): self { $this->structureChecked = $v; return $this; }

    public function isWiringSecured(): bool { return $this->wiringSecured; }
    public function setWiringSecured(bool $v): self { $this->wiringSecured = $v; return $this; }

    public function isInverterChecked(): bool { return $this->inverterChecked; }
    public function setInverterChecked(bool $v): self { $this->inverterChecked = $v; return $this; }

    public function isDebrisRemoved(): bool { return $this->debrisRemoved; }
    public function setDebrisRemoved(bool $v): self { $this->debrisRemoved = $v; return $this; }

    public function getBeforePhotos(): array { return $this->beforePhotos ?? []; }
    public function setBeforePhotos(?array $paths): self { $this->beforePhotos = $paths ?? []; return $this; }

    public function getAfterPhotos(): array { return $this->afterPhotos ?? []; }
    public function setAfterPhotos(?array $paths): self { $this->afterPhotos = $paths ?? []; return $this; }

    public function getNote(): ?string { return $this->note; }
    public function setNote(?string $v): self { $this->note = $v; return $this; }

    public function isPublishOnSite(): bool { return $this->publishOnSite; }
    public function setPublishOnSite(bool $v): self { $this->publishOnSite = $v; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
