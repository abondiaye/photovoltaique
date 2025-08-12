<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class RealisationDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $image,
        public string $location,
        public \DateTimeImmutable $doneAt,
        public \DateTimeImmutable $createdAt,
    ) {}
}
