<?php
namespace App\Entity;

use App\Repository\LeadRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeadRepository::class)]
class Lead
{
    #[ORM\Id] #[ORM\GeneratedValue] #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private string $fullName;

    #[ORM\Column(length: 180)]
    private string $email;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $roofAreaM2 = null;

    #[ORM\Column(type: 'boolean')]
    private bool $hasShadow = false;

    #[ORM\Column(length: 20)]
    private string $status = 'new';

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct() { $this->createdAt = new \DateTimeImmutable(); }
    // + getters/setters…

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRoofAreaM2(): ?float
    {
        return $this->roofAreaM2;
    }

    public function setRoofAreaM2(?float $roofAreaM2): static
    {
        $this->roofAreaM2 = $roofAreaM2;

        return $this;
    }

    public function hasShadow(): ?bool
    {
        return $this->hasShadow;
    }

    public function setHasShadow(bool $hasShadow): static
    {
        $this->hasShadow = $hasShadow;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
