<?php

namespace App\Repository;

use App\Entity\TalkComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TalkComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TalkComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TalkComment[]    findAll()
 * @method TalkComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TalkCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TalkComment::class);
    }

    // /**
    //  * @return EventComment[] Returns an array of EventComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventComment
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
