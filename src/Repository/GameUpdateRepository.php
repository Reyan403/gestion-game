<?php

namespace App\Repository;

use App\Entity\GameUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameUpdate>
 */
class GameUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameUpdate::class);
    }

    public function findPendingGameUpdate()
    {
        return $this->createQueryBuilder('gu')
            ->join('gu.status', 's')
            ->addSelect('s')
            ->andWhere('s.name = :name')
            ->setParameter('name', 'pending')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return GameUpdate[] Returns an array of GameUpdate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GameUpdate
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
