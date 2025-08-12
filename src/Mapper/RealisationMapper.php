<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Realisation;
use App\Dto\RealisationDto;

final class RealisationMapper
{
    public static function toDto(Realisation $entity): RealisationDto
    {
        return new RealisationDto(
            id: $entity->getId(),
            title: $entity->getTitle(),
            description: $entity->getDescription(),
            image: $entity->getImage(),
            location: $entity->getLocation(),
            doneAt: $entity->getDoneAt(),
            createdAt: $entity->getCreatedAt(),
        );
    }
}
    