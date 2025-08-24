<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\AvailabilitySlotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvailabilitySlotRepository::class)]
#[ORM\Table(name: 'availability_slot')]
#[ORM\Index(columns: ['start_at'], name: 'idx_slot_start')]
#[ORM\Index(columns: ['end_at'], name: 'idx_slot_end')]
#[ORM\HasLifecycleCallbacks]
final class AvailabilitySlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    private \DateTimeImmutable $startAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    #[Assert\Expression('value > this.getStartAt()', message: 'La fin doit être après le début.')]
    private \DateTimeImmutable $endAt;

    #[ORM\Column(type: 'smallint', options: ['unsigned' => true])]
    #[Assert\Positive]
    private int $capacity = 1;

    #[ORM\Column(type: 'smallint', options: ['unsigned' => true])]
    #[Assert\GreaterThanOrEqual(0)]
    private int $booked = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isClosed = false;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(\DateTimeImmutable $startAt, \DateTimeImmutable $endAt, int $capacity = 1, ?string $label = null)
    {
        $this->startAt = $startAt;
        $this->endAt   = $endAt;
        $this->capacity = $capacity;
        $this->label = $label;
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new \DateTimeImmutable();
        $this->createdAt = $this->createdAt ?? $now;
        $this->updatedAt = $now;
        if ($this->booked < 0) { $this->booked = 0; }
        if ($this->capacity < 1) { $this->capacity = 1; }
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
        if ($this->booked < 0) { $this->booked = 0; }
        if ($this->booked > $this->capacity) { $this->booked = $this->capacity; }
    }

    public function getId(): ?int { return $this->id; }

    public function getStartAt(): \DateTimeImmutable { return $this->startAt; }
    public function setStartAt(\DateTimeImmutable $v): self { $this->startAt = $v; return $this; }

    public function getEndAt(): \DateTimeImmutable { return $this->endAt; }
    public function setEndAt(\DateTimeImmutable $v): self { $this->endAt = $v; return $this; }

    public function getCapacity(): int { return $this->capacity; }
    public function setCapacity(int $v): self { $this->capacity = max(1, $v); return $this; }

    public function getBooked(): int { return $this->booked; }
    public function setBooked(int $v): self { $this->booked = max(0, $v); return $this; }

    public function getLabel(): ?string { return $this->label; }
    public function setLabel(?string $v): self { $this->label = $v; return $this; }

    public function isClosed(): bool { return $this->isClosed; }
    public function setClosed(bool $v): self { $this->isClosed = $v; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    public function hasRemaining(): bool
    {
        return !$this->isClosed && ($this->booked < $this->capacity);
    }

    public function bookOne(): void
    {
        if (!$this->hasRemaining()) {
            throw new \RuntimeException('Plus de place sur ce créneau.');
        }
        $this->booked++;
    }

    public function cancelOne(): void
    {
        if ($this->booked > 0) {
            $this->booked--;
        }
    }

    public function covers(\DateTimeImmutable $date): bool
    {
        return $date >= $this->startAt && $date < $this->endAt;
    }

    public function __toString(): string
    {
        return sprintf('%s (%d/%d)', $this->startAt->format('d/m H:i').'→'.$this->endAt->format('H:i'), $this->booked, $this->capacity);
    }
}
