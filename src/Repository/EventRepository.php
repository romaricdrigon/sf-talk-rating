<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[]
     */
    public function findOnlineByBadgeUrl(array $badgeUrls): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.status = :online')
            ->andWhere('e.badgeUrl IN (:urls)')
            ->setParameters([
                'online' => Event::STATUS_ONLINE,
                'urls' => $badgeUrls,
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
