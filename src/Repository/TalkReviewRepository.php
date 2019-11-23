<?php

namespace App\Repository;

use App\Entity\TalkReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TalkReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method TalkReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method TalkReview[]    findAll()
 * @method TalkReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TalkReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TalkReview::class);
    }
}
