<?php

namespace App\Entity;

use App\Repository\MaintenanceRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaintenanceRequestRepository::class)]
#[ORM\Table(name: 'maintenance_request')]
#[ORM\Index(columns: ['status'], name: 'idx_mr_status')]
#[ORM\Index(columns: ['requested_date'], name: 'idx_mr_requested_date')]
#[ORM\Index(columns: ['created_at'], name: 'idx_mr_created_at')]
#[ORM\HasLifecycleCallbacks]
class MaintenanceRequest
{
    // Types de demande
    public const TYPE_CLEANING   = 'cleaning';
    public const TYPE_INSPECTION = 'inspection';
    public const TYPE_REPAIR     = 'repair';

    // Statuts de suivi
    public const STATUS_PENDING   = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_DONE      = 'done';
    public const STATUS_CANCELLED = 'cancelled';

    public const ALLOWED_TYPES  = [self::TYPE_CLEANING, self::TYPE_INSPECTION, self::TYPE_REPAIR];
    public const ALLOWED_STATUS = [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_DONE, self::STATUS_CANCELLED];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // --------- Contact ---------
    #[ORM\Column(length: 120)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    private string $fullName;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    private string $email;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(max: 30)]
    private ?string $phone = null;

    // --------- Adresse ---------
    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 180)]
    private string $addressLine1;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(max: 180)]
    private ?string $addressLine2 = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $city;

    #[ORM\Column(length: 12)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 12)]
    // Exemple FR: 5 chiffres. Adapte si besoin.
    #[Assert\Regex(pattern: '/^[0-9A-Za-z\- ]+$/')]
    private string $postalCode;

    // --------- Demande ---------
    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: self::ALLOWED_TYPES)]
    private string $type = self::TYPE_CLEANING;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    /**
     * Date souhaitée par l’utilisateur (calendrier)
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    private \DateTimeImmutable $requestedDate;

    /**
     * Photos uploadées (URLs ou chemins). On stocke un tableau.
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $photos = [];

    // --------- Suivi ---------
    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: self::ALLOWED_STATUS)]
    private string $status = self::STATUS_PENDING;

    // --------- Timestamps ---------
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        // Par défaut, si rien n’est fourni, on met requestedDate à maintenant (tu peux préférer null + nullable: true)
        $this->requestedDate = $now;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new \DateTimeImmutable();
        $this->createdAt = $this->createdAt ?? $now;
        $this->updatedAt = $now;

        // garde-fous
        if (!\in_array($this->type, self::ALLOWED_TYPES, true)) {
            $this->type = self::TYPE_CLEANING;
        }
        if (!\in_array($this->status, self::ALLOWED_STATUS, true)) {
            $this->status = self::STATUS_PENDING;
        }
        if ($this->photos === null) {
            $this->photos = [];
        }
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
        if ($this->photos === null) {
            $this->photos = [];
        }
    }

    public function __toString(): string
    {
        // utile dans les listes (admin)
        return sprintf('#%d — %s — %s (%s)', $this->id, $this->fullName, $this->city, $this->status);
    }

    // ---------------- Getters / Setters ----------------

    public function getId(): ?int { return $this->id; }

    public function getFullName(): string { return $this->fullName; }
    public function setFullName(string $v): self { $this->fullName = $v; return $this; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $v): self { $this->email = $v; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $v): self { $this->phone = $v; return $this; }

    public function getAddressLine1(): string { return $this->addressLine1; }
    public function setAddressLine1(string $v): self { $this->addressLine1 = $v; return $this; }

    public function getAddressLine2(): ?string { return $this->addressLine2; }
    public function setAddressLine2(?string $v): self { $this->addressLine2 = $v; return $this; }

    public function getCity(): string { return $this->city; }
    public function setCity(string $v): self { $this->city = $v; return $this; }

    public function getPostalCode(): string { return $this->postalCode; }
    public function setPostalCode(string $v): self { $this->postalCode = $v; return $this; }

    public function getType(): string { return $this->type; }
    public function setType(string $v): self {
        $this->type = \in_array($v, self::ALLOWED_TYPES, true) ? $v : self::TYPE_CLEANING;
        return $this;
    }

    public function getComment(): ?string { return $this->comment; }
    public function setComment(?string $v): self { $this->comment = $v; return $this; }

    public function getRequestedDate(): \DateTimeImmutable { return $this->requestedDate; }
    public function setRequestedDate(\DateTimeImmutable $v): self { $this->requestedDate = $v; return $this; }

    public function getPhotos(): array { return $this->photos ?? []; }
    public function setPhotos(?array $v): self { $this->photos = $v ?? []; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $v): self {
        $this->status = \in_array($v, self::ALLOWED_STATUS, true) ? $v : self::STATUS_PENDING;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $v): self { $this->createdAt = $v; return $this; }

    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $v): self { $this->updatedAt = $v; return $this; }
}
