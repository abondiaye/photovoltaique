<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\AvailabilitySlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class AvailabilitySlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvailabilitySlot::class);
    }

    public function findCovering(\DateTimeImmutable $date): ?AvailabilitySlot
    {
        return $this->createQueryBuilder('s')
            ->andWhere(':d >= s.startAt AND :d < s.endAt')
            ->andWhere('s.isClosed = false')
            ->setParameter('d', $date)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /** @return AvailabilitySlot[] */
    public function findAvailablesBetween(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.startAt >= :from AND s.endAt <= :to')
            ->andWhere('s.isClosed = false')
            ->orderBy('s.startAt', 'ASC')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()->getResult();
    }

    /** @return array<int, array{id:int,title:string,start:string,end:string,color:string}> */
    public function calendarFeed(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        $slots = $this->createQueryBuilder('s')
            ->andWhere('s.startAt >= :from AND s.endAt <= :to')
            ->orderBy('s.startAt', 'ASC')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()->getResult();

        $out = [];
        foreach ($slots as $slot) {
            \assert($slot instanceof AvailabilitySlot);
            $color = $slot->isClosed() ? '#6c757d' : ($slot->hasRemaining() ? '#198754' : '#dc3545');
            $out[] = [
                'id' => $slot->getId(),
                'title' => trim(($slot->getLabel() ?? 'Créneau').' '.$slot->getBooked().'/'.$slot->getCapacity()),
                'start' => $slot->getStartAt()->format('c'),
                'end'   => $slot->getEndAt()->format('c'),
                'color' => $color,
            ];
        }
        return $out;
    }
}
