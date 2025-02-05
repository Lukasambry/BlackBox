<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function getPublicActiveRooms()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->where('r.isActive = :isActive')
            ->andWhere('r.isPrivate = :isPrivate')
            ->andWhere('r.currentState != :currentState')
            ->orderBy('r.created_at', 'DESC')
            ->setParameter('isActive', true)
            ->setParameter('isPrivate', false)
            ->setParameter('currentState', Room::STATE_FINISHED);

        return $qb->getQuery()->getResult();
    }
}
